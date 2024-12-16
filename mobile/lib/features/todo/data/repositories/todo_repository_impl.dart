import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import 'package:flutter/foundation.dart' show kIsWeb;
import 'package:http_parser/http_parser.dart';
import '../../domain/entities/todo.dart';
import '../../domain/repositories/todo_repository.dart';
import '../models/todo_model.dart';

class TodoRepositoryImpl implements TodoRepository {
  final String baseUrl = 'http://127.0.0.1:8000/api';
  final http.Client client;

  TodoRepositoryImpl({required this.client});

  Future<String> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');
    if (token == null) throw Exception('User not authenticated');
    
    return token;
  }

  Future<Map<String, String>> _getHeaders() async {
    final token = await _getToken();
    return {
      'Authorization': 'Bearer $token',
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    };
  }

  Future<String?> _getUserId() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('id');
  }

  @override
  Future<List<Todo>> getTodos() async {
    try {
      final headers = await _getHeaders();
      final userId = await _getUserId();
      if (userId == null) throw Exception('User not authenticated');

      final response = await client.get(
        Uri.parse('$baseUrl/todos/$userId'),
        headers: headers,
      );

      if (response.statusCode == 200) {
        final List<dynamic> jsonList = json.decode(response.body);
        return jsonList.map((json) => TodoModel.fromJson(json)).toList();
      } else {
        throw Exception('Failed to load todos');
      }
    } catch (e) {
      throw Exception('Failed to load todos: $e');
    }
  }

  @override
  Future<Todo> getTodoById(int id) async {
    try {
      final headers = await _getHeaders();
      final response = await client.get(
        Uri.parse('$baseUrl/todos/$id'),
        headers: headers,
      );

      if (response.statusCode == 200) {
        return TodoModel.fromJson(json.decode(response.body));
      } else {
        throw Exception('Failed to load todo');
      }
    } catch (e) {
      throw Exception('Failed to load todo: $e');
    }
  }

  @override
  Future<void> createTodo(Todo todo) async {
    try {
      final headers = await _getHeaders();
      final userId = await _getUserId();
      if (userId == null) throw Exception('User not authenticated');

      Map<String, dynamic> todoData = {
        'owner_id': userId,
        'title': todo.title,
        'description': todo.description,
        'dueDate': todo.dueDate.toIso8601String(),
        'priority': todo.priority,
        'is_completed': todo.isCompleted ? 1 : 0,
      };

      if (todo.image != null && todo.image!.isNotEmpty) {
        todoData['image'] = todo.image;
      }

      final response = await client.post(
        Uri.parse('$baseUrl/todos'),
        headers: {
          'Authorization': headers['Authorization']!,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: json.encode(todoData),
      );

      print('Response status: ${response.statusCode}');
      print('Response body: ${response.body}');

      if (response.statusCode != 201) {
        throw Exception('Failed to create todo: ${response.body}');
      }
    } catch (e) {
      print('Error creating todo: $e');
      throw Exception('Failed to create todo: $e');
    }
  }

  @override
  Future<void> updateTodo(Todo todo) async {
    try {
      final headers = await _getHeaders();
      final userId = await _getUserId();
      if (userId == null) throw Exception('User not authenticated');

      Map<String, dynamic> todoData = {
        'owner_id': userId,
        'title': todo.title,
        'description': todo.description,
        'due_date': todo.dueDate.toIso8601String(),
        'priority': todo.priority,
        'is_completed': todo.isCompleted ? 1 : 0,
      };

      if (todo.image != null && todo.image!.isNotEmpty) {
        todoData['image'] = todo.image;
      }

      final response = await client.patch(
        Uri.parse('$baseUrl/todos/${todo.id}'),
        headers: {
          'Authorization': headers['Authorization']!,
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
        body: json.encode(todoData),
      );

      print('Update Response status: ${response.statusCode}');
      print('Update Response body: ${response.body}');

      if (response.statusCode != 200) {
        throw Exception('Failed to update todo: ${response.body}');
      }
    } catch (e) {
      print('Error updating todo: $e');
      throw Exception('Failed to update todo: $e');
    }
  }

  @override
  Future<void> deleteTodo(int id) async {
    try {
      final headers = await _getHeaders();
      final response = await client.delete(
        Uri.parse('$baseUrl/todos/$id'),
        headers: headers,
      );

      print('Delete Response status: ${response.statusCode}');
      print('Delete Response body: ${response.body}');

      if (response.statusCode != 200) {
        throw Exception('Failed to delete todo: ${response.body}');
      }
    } catch (e) {
      print('Error deleting todo: $e');
      throw Exception('Failed to delete todo: $e');
    }
  }

  @override
  Future<void> toggleTodoComplete(int id) async {
    try {
      final todo = await getTodoById(id);
      final updatedTodo = TodoModel(
        id: todo.id,
        title: todo.title,
        description: todo.description,
        dueDate: todo.dueDate,
        priority: todo.priority,
        isCompleted: !todo.isCompleted,
        image: todo.image,
      );

      await updateTodo(updatedTodo);
    } catch (e) {
      throw Exception('Failed to toggle todo completion: $e');
    }
  }
}

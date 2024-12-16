import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';
import '../../domain/repositories/auth_repository.dart';

class AuthRepositoryImpl implements AuthRepository {
  final String baseUrl = 'http://127.0.0.1:8000/api';
  final http.Client client;
  static const String _tokenKey = 'auth_token';

  AuthRepositoryImpl({required this.client});

  Map<String, String> get _headers => {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  };

  @override
  Future<void> login(String email, String password) async {
    try {
      final response = await client.post(
        Uri.parse('$baseUrl/login'),
        headers: _headers,
        body: json.encode({
          'email': email,
          'password': password,
        }),
      );

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString(_tokenKey, data['token']);
        await prefs.setString('id', data['user']['id'].toString());
      } else {
        throw Exception('Invalid credentials');
      }
    } catch (e) {
      throw Exception('Failed to login: $e');
    }
  }

  @override
  Future<void> register(String name, String email, String password) async {
    try {
      print('Starting registration...');
      final url = Uri.parse('$baseUrl/register');

      // Send the registration request
      final response = await http.post(
        url,
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: jsonEncode({
          'name': name,
          'email': email,
          'password': password,
          'password_confirmation': password,
        }),
      );

      print('Registration response status: ${response.statusCode}');
      print('Registration response body: ${response.body}');

      if (response.statusCode == 201 || response.statusCode == 200) {
        final data = jsonDecode(response.body);
        if (data['token'] != null) {
          final prefs = await SharedPreferences.getInstance();
          await prefs.setString(_tokenKey, data['token']);
          print('Registration successful, token stored');
        }
      } else {
        final error = jsonDecode(response.body);
        throw Exception(error['message'] ?? 'Registration failed');
      }
    } catch (e) {
      print('Registration error: $e');
      rethrow;
    }
  }

  @override
  Future<void> logout() async {
    try {
      final prefs = await SharedPreferences.getInstance();
      final token = prefs.getString(_tokenKey);

      if (token != null) {
        await client.post(
          Uri.parse('$baseUrl/logout'),
          headers: {
            'Accept': 'application/json',
            'Authorization': 'Bearer $token',
          },
        );
      }

      await prefs.remove(_tokenKey);
      await prefs.clear();
    } catch (e) {
      throw Exception('Failed to logout: $e');
    }
  }

  @override
  Future<bool> isAuthenticated() async {
    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString(_tokenKey);
    return token != null;
  }
}

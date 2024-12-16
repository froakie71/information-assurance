import '../../domain/entities/todo.dart';

class TodoModel extends Todo {
  TodoModel({
    required int id,
    required String title,
    required String description,
    required DateTime dueDate,
    required String priority,
    required bool isCompleted,
    String? image,
    String? createdAt,
    String? updatedAt,
  }) : super(
          id: id,
          title: title,
          description: description,
          dueDate: dueDate,
          priority: priority,
          isCompleted: isCompleted,
          image: image,
          createdAt: createdAt,
          updatedAt: updatedAt,
        );

  factory TodoModel.fromJson(Map<String, dynamic> json) {
    return TodoModel(
      id: json['id'],
      title: json['title'],
      description: json['description'],
      dueDate: DateTime.parse(json['due_date']),
      priority: json['priority'],
      image: json['image'],
      isCompleted: json['is_completed'] == true || json['is_completed'] == 1,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'title': title,
      'description': description,
      'due_date': dueDate.toIso8601String(),
      'priority': priority,
      'is_completed': isCompleted ? 1 : 0,
      'image': image,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}

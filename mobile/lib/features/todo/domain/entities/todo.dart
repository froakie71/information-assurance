class Todo {
  final int id;
  final String title;
  final String description;
  final DateTime dueDate;
  final String priority;
  final bool isCompleted;
  final String? image;
  final String? createdAt;
  final String? updatedAt;

  Todo({
    required this.id,
    required this.title,
    required this.description,
    required this.dueDate,
    required this.priority,
    required this.isCompleted,
    this.image,
    this.createdAt,
    this.updatedAt,
  });
}

import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import '../bloc/todo_bloc.dart';
import '../../domain/entities/todo.dart';
import 'package:cached_network_image/cached_network_image.dart';

class TodoHistoryPage extends StatefulWidget {
  const TodoHistoryPage({Key? key}) : super(key: key);

  @override
  State<TodoHistoryPage> createState() => _TodoHistoryPageState();
}

class _TodoHistoryPageState extends State<TodoHistoryPage> {
  @override
  void initState() {
    super.initState();
    context.read<TodoBloc>().add(LoadTodos());
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Todo History'),
      ),
      body: BlocBuilder<TodoBloc, TodoState>(
        builder: (context, state) {
          if (state is TodoLoading) {
            return const Center(child: CircularProgressIndicator());
          } else if (state is TodosLoaded) {
            final completedTodos = state.todos.where((todo) => todo.isCompleted).toList();
            
            if (completedTodos.isEmpty) {
              return const Center(
                child: Text('No completed todos yet'),
              );
            }

            return ListView.builder(
              itemCount: completedTodos.length,
              itemBuilder: (context, index) {
                final todo = completedTodos[index];
                return Card(
                  margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  child: ListTile(
                    leading: todo.image != null && todo.image!.isNotEmpty
                        ? ClipRRect(
                            borderRadius: BorderRadius.circular(4),
                            child: CachedNetworkImage(
                              imageUrl: todo.image!,
                              width: 50,
                              height: 50,
                              fit: BoxFit.cover,
                            ),
                          )
                        : const Icon(Icons.check_circle, color: Colors.green),
                    title: Text(
                      todo.title,
                      style: const TextStyle(
                        decoration: TextDecoration.lineThrough,
                      ),
                    ),
                    subtitle: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(todo.description),
                        const SizedBox(height: 4),
                        Row(
                          children: [
                            Icon(Icons.calendar_today, size: 16, color: Colors.grey[600]),
                            const SizedBox(width: 4),
                            Text(
                              'Completed on ${todo.dueDate.toString().split(' ')[0]}',
                              style: TextStyle(
                                fontSize: 12,
                                color: Colors.grey[600],
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    trailing: Container(
                      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                      decoration: BoxDecoration(
                        color: _getPriorityColor(todo.priority).withOpacity(0.2),
                        borderRadius: BorderRadius.circular(4),
                      ),
                      child: Text(
                        todo.priority,
                        style: TextStyle(
                          color: _getPriorityColor(todo.priority),
                          fontSize: 12,
                        ),
                      ),
                    ),
                  ),
                );
              },
            );
          } else if (state is TodoError) {
            return Center(child: Text(state.message));
          }
          return const SizedBox();
        },
      ),
    );
  }

  Color _getPriorityColor(String priority) {
    switch (priority) {
      case 'High':
        return Colors.red;
      case 'Medium':
        return Colors.orange;
      case 'Low':
        return Colors.green;
      default:
        return Colors.grey;
    }
  }
} 
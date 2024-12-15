import 'package:flutter_bloc/flutter_bloc.dart';
import '../../domain/repositories/todo_repository.dart';
import '../../domain/entities/todo.dart';

// Events
abstract class TodoEvent {}

class LoadTodos extends TodoEvent {}
class AddTodo extends TodoEvent {
  final Todo todo;
  AddTodo(this.todo);
}
class UpdateTodo extends TodoEvent {
  final Todo todo;
  UpdateTodo(this.todo);
}
class DeleteTodo extends TodoEvent {
  final int id;
  DeleteTodo(this.id);
}
class ToggleTodoComplete extends TodoEvent {
  final int id;
  ToggleTodoComplete(this.id);
}

// States
abstract class TodoState {}

class TodoInitial extends TodoState {}
class TodoLoading extends TodoState {}
class TodosLoaded extends TodoState {
  final List<Todo> todos;
  final int totalTasks;
  final int completedTasks;
  final int pendingTasks;

  TodosLoaded(this.todos)
      : totalTasks = todos.length,
        completedTasks = todos.where((todo) => todo.isCompleted).length,
        pendingTasks = todos.where((todo) => !todo.isCompleted).length;
}
class TodoError extends TodoState {
  final String message;
  TodoError(this.message);
}

// Bloc
class TodoBloc extends Bloc<TodoEvent, TodoState> {
  final TodoRepository repository;

  TodoBloc({required this.repository}) : super(TodoInitial()) {
    on<LoadTodos>((event, emit) async {
      emit(TodoLoading());
      try {
        final todos = await repository.getTodos();
        emit(TodosLoaded(todos));
      } catch (e) {
        emit(TodoError(e.toString()));
      }
    });

    on<AddTodo>((event, emit) async {
      try {
        await repository.createTodo(event.todo);
        final todos = await repository.getTodos();
        emit(TodosLoaded(todos));
      } catch (e) {
        emit(TodoError(e.toString()));
      }
    });

    on<UpdateTodo>((event, emit) async {
      try {
        await repository.updateTodo(event.todo);
        final todos = await repository.getTodos();
        emit(TodosLoaded(todos));
      } catch (e) {
        emit(TodoError(e.toString()));
      }
    });

    on<DeleteTodo>((event, emit) async {
      try {
        await repository.deleteTodo(event.id);
        final todos = await repository.getTodos();
        emit(TodosLoaded(todos));
      } catch (e) {
        emit(TodoError(e.toString()));
      }
    });

    on<ToggleTodoComplete>((event, emit) async {
      try {
        await repository.toggleTodoComplete(event.id);
        final todos = await repository.getTodos();
        emit(TodosLoaded(todos));
      } catch (e) {
        emit(TodoError(e.toString()));
      }
    });
  }
}

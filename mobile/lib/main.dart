import 'package:flutter/material.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:http/http.dart' as http;
import 'features/todo/data/repositories/todo_repository_impl.dart';
import 'features/todo/domain/repositories/todo_repository.dart';
import 'features/todo/presentation/bloc/todo_bloc.dart';
import 'features/todo/presentation/pages/todo_list_page.dart';
import 'features/auth/data/repositories/auth_repository_impl.dart';
import 'features/auth/domain/repositories/auth_repository.dart';
import 'features/auth/presentation/bloc/auth_bloc.dart';
import 'features/auth/presentation/pages/login_page.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  final client = http.Client();
  final authRepository = AuthRepositoryImpl(client: client);
  final todoRepository = TodoRepositoryImpl(client: client);

  runApp(MyApp(
    authRepository: authRepository,
    todoRepository: todoRepository,
  ));
}

class MyApp extends StatelessWidget {
  final AuthRepository authRepository;
  final TodoRepository todoRepository;

  const MyApp({
    super.key,
    required this.authRepository,
    required this.todoRepository,
  });

  @override
  Widget build(BuildContext context) {
    return MultiBlocProvider(
      providers: [
        BlocProvider(
          create: (context) => AuthBloc(
            repository: authRepository,
          )..add(CheckAuthStatus()),
        ),
        BlocProvider(
          create: (context) => TodoBloc(
            repository: todoRepository,
          ),
        ),
      ],
      child: MaterialApp(
        title: 'Todo Dashboard',
        debugShowCheckedModeBanner: false,
        theme: ThemeData(
          colorScheme: ColorScheme.fromSeed(seedColor: Colors.blue),
          useMaterial3: true,
        ),
        home: BlocBuilder<AuthBloc, AuthState>(
          builder: (context, state) {
            if (state is AuthLoading) {
              return const Scaffold(
                body: Center(child: CircularProgressIndicator()),
              );
            } else if (state is Authenticated) {
              context.read<TodoBloc>().add(LoadTodos());
              return const TodoListPage();
            }
            return const LoginPage();
          },
        ),
      ),
    );
  }
}

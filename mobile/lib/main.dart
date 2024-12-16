import 'package:flutter/material.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:flutter_web_plugins/flutter_web_plugins.dart';
import 'injection_container.dart' as di;
import 'features/auth/presentation/bloc/auth_bloc.dart';
import 'features/todo/presentation/bloc/todo_bloc.dart';
import 'features/todo/presentation/pages/todo_list_page.dart';
import 'features/auth/presentation/pages/login_page.dart';

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  if (kIsWeb) {
    usePathUrlStrategy();
    await Future.delayed(const Duration(milliseconds: 100)); // Add small delay for web initialization
  }
  
  await di.init();
  
  runApp(MultiBlocProvider(
    providers: [
      BlocProvider<AuthBloc>(
        create: (context) => di.sl<AuthBloc>()..add(CheckAuthStatus()),
      ),
      BlocProvider<TodoBloc>(
        create: (context) => di.sl<TodoBloc>(),
      ),
    ],
    child: const MyApp(),
  ));
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Todo App',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.deepPurple),
        useMaterial3: true,
      ),
      home: BlocBuilder<AuthBloc, AuthState>(
        builder: (context, state) {
          if (state is AuthLoading) {
            return const Scaffold(
              body: Center(
                child: CircularProgressIndicator(),
              ),
            );
          }
          
          if (state is Authenticated) {
            return const TodoListPage();
          }
          
          return const LoginPage();
        },
      ),
    );
  }
}

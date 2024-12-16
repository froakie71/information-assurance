import 'package:get_it/get_it.dart';
import 'package:http/http.dart' as http;
import 'package:mobile/features/auth/data/repositories/auth_repository_impl.dart';
import 'package:mobile/features/auth/domain/repositories/auth_repository.dart';
import 'package:mobile/features/auth/presentation/bloc/auth_bloc.dart';
import 'package:mobile/features/todo/data/repositories/todo_repository_impl.dart';
import 'package:mobile/features/todo/domain/repositories/todo_repository.dart';
import 'package:mobile/features/todo/presentation/bloc/todo_bloc.dart';

final sl = GetIt.instance;

Future<void> init() async {
  // Blocs
  sl.registerFactory(
    () => AuthBloc(repository: sl()),
  );
  
  sl.registerFactory(
    () => TodoBloc(repository: sl()),
  );

  // Repositories
  sl.registerLazySingleton<AuthRepository>(
    () => AuthRepositoryImpl(client: sl()),
  );
  
  sl.registerLazySingleton<TodoRepository>(
    () => TodoRepositoryImpl(client: sl()),
  );

  // External
  sl.registerLazySingleton(() => http.Client());
}

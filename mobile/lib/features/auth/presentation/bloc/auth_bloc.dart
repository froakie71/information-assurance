import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:equatable/equatable.dart';
import '../../domain/repositories/auth_repository.dart';

// Events
abstract class AuthEvent extends Equatable {
  const AuthEvent();

  @override
  List<Object> get props => [];
}

class CheckAuthStatus extends AuthEvent {}

class Login extends AuthEvent {
  final String email;
  final String password;

  const Login({required this.email, required this.password});

  @override
  List<Object> get props => [email, password];
}

class Register extends AuthEvent {
  final String name;
  final String email;
  final String password;

  const Register({
    required this.name,
    required this.email,
    required this.password,
  });

  @override
  List<Object> get props => [name, email, password];
}

class Logout extends AuthEvent {}

// States
abstract class AuthState extends Equatable {
  const AuthState();

  @override
  List<Object> get props => [];
}

class AuthInitial extends AuthState {}

class AuthLoading extends AuthState {}

class Authenticated extends AuthState {}

class Registered extends AuthState {}

class Unauthenticated extends AuthState {}

class AuthError extends AuthState {
  final String message;

  const AuthError(this.message);

  @override
  List<Object> get props => [message];
}

// Bloc
class AuthBloc extends Bloc<AuthEvent, AuthState> {
  final AuthRepository repository;

  AuthBloc({required this.repository}) : super(AuthInitial()) {
    on<CheckAuthStatus>((event, emit) async {
      emit(AuthLoading());
      try {
        final isLoggedIn = await repository.isLoggedIn();
        emit(isLoggedIn ? Authenticated() : Unauthenticated());
      } catch (e) {
        emit(AuthError(e.toString()));
      }
    });

    on<Login>((event, emit) async {
      emit(AuthLoading());
      try {
        await repository.login(event.email, event.password);
        emit(Authenticated());
      } catch (e) {
        emit(AuthError(e.toString()));
      }
    });

    on<Register>((event, emit) async {
      emit(AuthLoading());
      try {
        await repository.register(event.name, event.email, event.password);
        emit(Registered());
      } catch (e) {
        emit(AuthError(e.toString()));
      }
    });

    on<Logout>((event, emit) async {
      emit(AuthLoading());
      try {
        await repository.logout();
        emit(Unauthenticated());
      } catch (e) {
        emit(AuthError(e.toString()));
      }
    });
  }
}

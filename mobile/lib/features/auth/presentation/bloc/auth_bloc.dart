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
    on<CheckAuthStatus>(_onCheckAuthStatus);
    on<Login>(_onLogin);
    on<Register>(_onRegister);
    on<Logout>(_onLogout);
  }

  void _onCheckAuthStatus(CheckAuthStatus event, Emitter<AuthState> emit) async {
    emit(AuthLoading());
    try {
      final isAuthenticated = await repository.isAuthenticated();
      emit(isAuthenticated ? Authenticated() : Unauthenticated());
    } catch (e) {
      print('AuthBloc: Check auth status failed - $e');
      emit(Unauthenticated());
    }
  }

  void _onLogin(Login event, Emitter<AuthState> emit) async {
    emit(AuthLoading());
    try {
      print('AuthBloc: Attempting login...');
      await repository.login(event.email, event.password);
      print('AuthBloc: Login successful');
      emit(Authenticated());
    } catch (e) {
      print('AuthBloc: Login failed - $e');
      emit(AuthError(e.toString()));
    }
  }

  void _onRegister(Register event, Emitter<AuthState> emit) async {
    emit(AuthLoading());
    try {
      print('AuthBloc: Attempting registration...');
      await repository.register(event.name, event.email, event.password);
      print('AuthBloc: Registration successful');
      emit(Authenticated());
    } catch (e) {
      print('AuthBloc: Registration failed - $e');
      emit(AuthError(e.toString()));
    }
  }

  void _onLogout(Logout event, Emitter<AuthState> emit) async {
    emit(AuthLoading());
    try {
      await repository.logout();
      emit(Unauthenticated());
    } catch (e) {
      emit(AuthError(e.toString()));
    }
  }
}

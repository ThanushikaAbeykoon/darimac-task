import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl =
      'http://127.0.0.1:8000/api'; // Update to production URL
  static const String registerEndpoint = '$baseUrl/register';
  static const String loginEndpoint = '$baseUrl/login';
  static const String logoutEndpoint = '$baseUrl/logout';
  static const String userEndpoint = '$baseUrl/user';
  static const String formsEndpoint = '$baseUrl/forms';

  // Get token from shared preferences
  Future<String?> _getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('auth_token');
  }

  // Store token in shared preferences
  Future<void> _storeToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('auth_token', token);
  }

  // Remove token on logout
  Future<void> _removeToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
  }

  // Register user
  Future<Map<String, dynamic>> register(
    String name,
    String email,
    String password,
    String passwordConfirmation,
  ) async {
    final response = await http.post(
      Uri.parse(registerEndpoint),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': passwordConfirmation,
      }),
    );
    if (response.statusCode == 201) {
      final data = jsonDecode(response.body);
      await _storeToken(data['token']);
      return data; // Returns {'token': '...', 'user': {...}}
    } else if (response.statusCode == 422) {
      final errors = jsonDecode(response.body)['errors'];
      throw Exception(errors.values.join('\n'));
    } else {
      throw Exception('Failed to register: ${response.reasonPhrase}');
    }
  }

  // Login user
  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse(loginEndpoint),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({'email': email, 'password': password}),
    );
    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      await _storeToken(data['token']);
      return data; // Returns {'token': '...', 'user': {...}}
    } else if (response.statusCode == 422) {
      final errors = jsonDecode(response.body)['errors'];
      throw Exception(errors.values.join('\n'));
    } else {
      throw Exception('Failed to login: ${response.reasonPhrase}');
    }
  }

  // Get authenticated user
  Future<Map<String, dynamic>> getUser() async {
    final token = await _getToken();
    if (token == null) throw Exception('Not authenticated');
    final response = await http.get(
      Uri.parse(userEndpoint),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );
    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to fetch user: ${response.reasonPhrase}');
    }
  }

  // Fetch submitted forms
  Future<List<Map<String, dynamic>>> getForms() async {
    final token = await _getToken();
    if (token == null) throw Exception('Not authenticated');
    final response = await http.get(
      Uri.parse(formsEndpoint),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );
    if (response.statusCode == 200) {
      return List<Map<String, dynamic>>.from(jsonDecode(response.body));
    } else {
      throw Exception('Failed to fetch forms: ${response.reasonPhrase}');
    }
  }

  // Submit form
  Future<Map<String, dynamic>> submitForm(
    String name,
    String address,
    String contact,
  ) async {
    final token = await _getToken();
    if (token == null) throw Exception('Not authenticated');
    final response = await http.post(
      Uri.parse(formsEndpoint),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'name': name,
        'address': address,
        'contact_number': contact,
      }),
    );
    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else if (response.statusCode == 422) {
      final errors = jsonDecode(response.body)['errors'];
      throw Exception(errors.values.join('\n'));
    } else {
      throw Exception('Failed to submit form: ${response.reasonPhrase}');
    }
  }

  // Get PDF URL (placeholder, requires backend implementation)
  Future<String> getPDFUrl(int formId) async {
    final token = await _getToken();
    if (token == null) throw Exception('Not authenticated');
    final response = await http.get(
      Uri.parse('$formsEndpoint/$formId/pdf-url'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );
    if (response.statusCode == 200) {
      return jsonDecode(response.body)['url'];
    } else {
      throw Exception('Failed to fetch PDF URL: ${response.reasonPhrase}');
    }
  }

  // Get QR code URL (placeholder, requires backend implementation)
  Future<String> getQRCodeUrl(int formId) async {
    final token = await _getToken();
    if (token == null) throw Exception('Not authenticated');
    final response = await http.get(
      Uri.parse('$formsEndpoint/$formId/qrcode-url'),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );
    if (response.statusCode == 200) {
      return jsonDecode(response.body)['url'];
    } else {
      throw Exception('Failed to fetch QR code URL: ${response.reasonPhrase}');
    }
  }

  // Logout user
  Future<void> logout() async {
    final token = await _getToken();
    if (token == null) return;
    await http.post(
      Uri.parse(logoutEndpoint),
      headers: {'Authorization': 'Bearer $token', 'Accept': 'application/json'},
    );
    await _removeToken();
  }

  // Placeholder for Google login
  Future<Map<String, dynamic>> googleLogin() async {
    throw UnimplementedError('Google login not implemented yet');
  }
}

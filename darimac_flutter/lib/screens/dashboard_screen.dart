import 'dart:convert';
import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:qr_flutter/qr_flutter.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:path_provider/path_provider.dart';
import 'package:open_file/open_file.dart';
import 'package:url_launcher/url_launcher.dart';
import 'dart:io';
import 'dart:ui' as ui;
import 'package:intl/intl.dart'; // For date formatting
import '../services/api_service.dart';
import '../constants/app_colors.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  final _formKey = GlobalKey<FormState>();
  late TextEditingController _nameController;
  late TextEditingController _addressController;
  late TextEditingController _contactController;
  bool _isLoading = false;
  Map<String, dynamic>? _userData;
  List<Map<String, dynamic>> _submittedForms = [];
  final _apiService = ApiService();
  String? _qrData;
  String? _pdfPath;

  @override
  void initState() {
    super.initState();
    _nameController = TextEditingController();
    _addressController = TextEditingController();
    _contactController = TextEditingController();
    _fetchUser();
    _fetchForms();
  }

  @override
  void dispose() {
    _nameController.dispose();
    _addressController.dispose();
    _contactController.dispose();
    super.dispose();
  }

  Future<void> _fetchUser() async {
    try {
      final userData = await _apiService.getUser();
      setState(() {
        _userData = userData;
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Error fetching user data: $e')));
      }
    }
  }

  Future<void> _fetchForms() async {
    try {
      final forms = await _apiService.getForms();
      setState(() {
        _submittedForms = List<Map<String, dynamic>>.from(forms);
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Error fetching forms: $e')));
      }
    }
  }

  Future<void> _submitForm() async {
    if (_formKey.currentState!.validate()) {
      setState(() {
        _isLoading = true;
      });
      try {
        final response = await _apiService.submitForm(
          _nameController.text,
          _addressController.text,
          _contactController.text,
        );
        final qrData = jsonEncode({
          'name': _nameController.text,
          'address': _addressController.text,
          'contact_number': _contactController.text,
        });
        setState(() {
          _qrData = qrData;
        });
        await _generatePdf();
        _showSuccessDialog(response);
        _nameController.clear();
        _addressController.clear();
        _contactController.clear();
        await _fetchForms(); // Refresh form list
      } catch (e) {
        if (mounted) {
          ScaffoldMessenger.of(
            context,
          ).showSnackBar(SnackBar(content: Text('Submission failed: $e')));
        }
      } finally {
        if (mounted) {
          setState(() {
            _isLoading = false;
          });
        }
      }
    }
  }

  Future<Uint8List> _generateQrImage(String data) async {
    try {
      final qrValidationResult = QrValidator.validate(
        data: data,
        version: QrVersions.auto,
        errorCorrectionLevel: QrErrorCorrectLevel.L,
      );
      if (!qrValidationResult.isValid) {
        throw Exception('Invalid QR code data');
      }
      final qrCode = qrValidationResult.qrCode;
      final painter = QrPainter.withQr(
        qr: qrCode!,
        emptyColor: Colors.white,
        gapless: true,
      );
      final imageData = await painter.toImageData(
        200,
        format: ui.ImageByteFormat.png,
      );
      return imageData!.buffer.asUint8List();
    } catch (e) {
      throw Exception('Error generating QR code: $e');
    }
  }

  Future<void> _generatePdf() async {
    try {
      final pdf = pw.Document();
      final qrImageData = await _generateQrImage(_qrData!);

      pdf.addPage(
        pw.Page(
          build:
              (pw.Context context) => pw.Column(
                crossAxisAlignment: pw.CrossAxisAlignment.start,
                children: [
                  pw.Text(
                    'Form Submission Details',
                    style: const pw.TextStyle(fontSize: 24),
                  ),
                  pw.Text(
                    'Date: ${DateFormat('yyyy-MM-dd HH:mm').format(DateTime.now())}',
                    style: const pw.TextStyle(fontSize: 16),
                  ),
                  pw.SizedBox(height: 20),
                  pw.Text(
                    'Name: ${_nameController.text}',
                    style: const pw.TextStyle(fontSize: 16),
                  ),
                  pw.Text(
                    'Address: ${_addressController.text}',
                    style: const pw.TextStyle(fontSize: 16),
                  ),
                  pw.Text(
                    'Contact Number: ${_contactController.text}',
                    style: const pw.TextStyle(fontSize: 16),
                  ),
                  pw.SizedBox(height: 20),
                  pw.Text('QR Code:', style: const pw.TextStyle(fontSize: 16)),
                  pw.Image(pw.MemoryImage(qrImageData)),
                ],
              ),
        ),
      );

      final output = await getTemporaryDirectory();
      final file = File(
        '${output.path}/form_details_${DateTime.now().millisecondsSinceEpoch}.pdf',
      );
      await file.writeAsBytes(await pdf.save());
      setState(() {
        _pdfPath = file.path;
      });
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Error generating PDF: $e')));
      }
    }
  }

  void _showSuccessDialog(Map<String, dynamic> formData) {
    if (!mounted) return;
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Form Submitted Successfully!'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text(
                'Your form has been submitted. What would you like to do next?',
              ),
              const SizedBox(height: 20),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  ElevatedButton.icon(
                    onPressed: () {
                      Navigator.of(context).pop();
                      _downloadPDF(formData['id']);
                    },
                    icon: const Icon(Icons.picture_as_pdf),
                    label: const Text('Download PDF'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.red,
                      foregroundColor: Colors.white,
                    ),
                  ),
                  ElevatedButton.icon(
                    onPressed: () {
                      Navigator.of(context).pop();
                      _showQRCode(formData['id']);
                    },
                    icon: const Icon(Icons.qr_code),
                    label: const Text('View QR Code'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.blue,
                      foregroundColor: Colors.white,
                    ),
                  ),
                ],
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Close'),
            ),
          ],
        );
      },
    );
  }

  Future<void> _downloadPDF(int formId) async {
    try {
      if (!mounted) return;
      setState(() {
        _isLoading = true;
      });
      final pdfUrl = await _apiService.getPDFUrl(formId);
      if (await canLaunchUrl(Uri.parse(pdfUrl))) {
        await launchUrl(Uri.parse(pdfUrl));
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
            content: Text('PDF download started'),
            backgroundColor: Colors.green,
          ),
        );
      } else {
        throw Exception('Could not launch $pdfUrl');
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('Error downloading PDF: $e'),
            backgroundColor: Colors.red,
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }

  void _showQRCode(int formId) {
    if (!mounted) return;
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('QR Code'),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text('Scan this QR code to view your form details:'),
              const SizedBox(height: 20),
              FutureBuilder<String>(
                future: _apiService.getQRCodeUrl(formId),
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return const CircularProgressIndicator();
                  } else if (snapshot.hasError) {
                    return Text('Error loading QR code: ${snapshot.error}');
                  } else {
                    return Image.network(
                      snapshot.data!,
                      width: 200,
                      height: 200,
                      loadingBuilder: (context, child, loadingProgress) {
                        if (loadingProgress == null) return child;
                        return const CircularProgressIndicator();
                      },
                      errorBuilder: (context, error, stackTrace) {
                        return const Text('Failed to load QR code image');
                      },
                    );
                  }
                },
              ),
            ],
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(),
              child: const Text('Close'),
            ),
          ],
        );
      },
    );
  }

  Future<void> _logout() async {
    try {
      await _apiService.logout();
      if (mounted) {
        Navigator.pushReplacementNamed(context, '/login');
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(
          context,
        ).showSnackBar(SnackBar(content: Text('Error logging out: $e')));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(
          _userData != null ? 'Welcome, ${_userData!['name']}' : 'Dashboard',
        ),
        backgroundColor: AppColors.primary,
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: _logout,
            tooltip: 'Logout',
          ),
        ],
      ),
      body:
          _isLoading
              ? const Center(child: CircularProgressIndicator())
              : SingleChildScrollView(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // User Info Section
                    if (_userData != null) ...[
                      Card(
                        child: Padding(
                          padding: const EdgeInsets.all(16.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'User Information',
                                style:
                                    Theme.of(context).textTheme.headlineSmall,
                              ),
                              const SizedBox(height: 8),
                              Text('Name: ${_userData!['name']}'),
                              Text('Email: ${_userData!['email']}'),
                            ],
                          ),
                        ),
                      ),
                      const SizedBox(height: 24),
                    ],

                    // Form Section
                    Card(
                      child: Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Form(
                          key: _formKey,
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                'Submit New Form',
                                style:
                                    Theme.of(context).textTheme.headlineSmall,
                              ),
                              const SizedBox(height: 16),
                              TextFormField(
                                controller: _nameController,
                                decoration: const InputDecoration(
                                  labelText: 'Full Name',
                                  hintText: 'Enter your full name',
                                  prefixIcon: Icon(Icons.person),
                                ),
                                validator:
                                    (value) =>
                                        value!.isEmpty
                                            ? 'Name is required'
                                            : null,
                              ),
                              const SizedBox(height: 16),
                              TextFormField(
                                controller: _addressController,
                                decoration: const InputDecoration(
                                  labelText: 'Address',
                                  hintText: 'Enter your complete address',
                                  prefixIcon: Icon(Icons.location_on),
                                ),
                                maxLines: 3,
                                validator:
                                    (value) =>
                                        value!.isEmpty
                                            ? 'Address is required'
                                            : null,
                              ),
                              const SizedBox(height: 16),
                              TextFormField(
                                controller: _contactController,
                                decoration: const InputDecoration(
                                  labelText: 'Contact Number',
                                  hintText: 'Enter your phone number',
                                  prefixIcon: Icon(Icons.phone),
                                ),
                                keyboardType: TextInputType.phone,
                                validator:
                                    (value) =>
                                        value!.isEmpty
                                            ? 'Contact number is required'
                                            : null,
                              ),
                              const SizedBox(height: 24),
                              SizedBox(
                                width: double.infinity,
                                child:
                                    _isLoading
                                        ? const Center(
                                          child: CircularProgressIndicator(),
                                        )
                                        : ElevatedButton.icon(
                                          onPressed: _submitForm,
                                          icon: const Icon(Icons.send),
                                          label: const Text('Submit Form'),
                                          style: ElevatedButton.styleFrom(
                                            padding: const EdgeInsets.symmetric(
                                              vertical: 16,
                                            ),
                                          ),
                                        ),
                              ),
                            ],
                          ),
                        ),
                      ),
                    ),

                    const SizedBox(height: 24),

                    // Submitted Forms Section
                    if (_submittedForms.isNotEmpty) ...[
                      Text(
                        'Submitted Forms',
                        style: Theme.of(context).textTheme.headlineSmall,
                      ),
                      const SizedBox(height: 16),
                      ListView.builder(
                        shrinkWrap: true,
                        physics: const NeverScrollableScrollPhysics(),
                        itemCount: _submittedForms.length,
                        itemBuilder: (context, index) {
                          final form = _submittedForms[index];
                          final createdAt =
                              form['created_at'] != null
                                  ? DateFormat('yyyy-MM-dd HH:mm').format(
                                    DateTime.parse(
                                      form['created_at'] as String,
                                    ),
                                  )
                                  : 'N/A';
                          return Card(
                            margin: const EdgeInsets.only(bottom: 12),
                            child: ListTile(
                              title: Text(form['name'] ?? 'Unknown'),
                              subtitle: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(form['address'] ?? ''),
                                  Text(
                                    'Contact: ${form['contact_number'] ?? ''}',
                                  ),
                                  Text('Submitted: $createdAt'),
                                ],
                              ),
                              trailing: Row(
                                mainAxisSize: MainAxisSize.min,
                                children: [
                                  IconButton(
                                    icon: const Icon(
                                      Icons.picture_as_pdf,
                                      color: Colors.red,
                                    ),
                                    onPressed: () => _downloadPDF(form['id']),
                                    tooltip: 'Download PDF',
                                  ),
                                  IconButton(
                                    icon: const Icon(
                                      Icons.qr_code,
                                      color: Colors.blue,
                                    ),
                                    onPressed: () => _showQRCode(form['id']),
                                    tooltip: 'View QR Code',
                                  ),
                                ],
                              ),
                            ),
                          );
                        },
                      ),
                    ],
                  ],
                ),
              ),
    );
  }
}

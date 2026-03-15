-- Datos de prueba para la clinica
USE clinica_db;

-- Especialidades
INSERT INTO Especialidades (NombreEspecialidad, Descripcion) VALUES
('Medicina General', 'Atencion medica general y preventiva'),
('Pediatria', 'Especialidad enfocada en ninos y adolescentes'),
('Cardiologia', 'Especialidad del corazon y sistema cardiovascular'),
('Dermatologia', 'Especialidad de la piel y sus enfermedades'),
('Ginecologia', 'Salud reproductiva femenina'),
('Traumatologia', 'Especialidad en huesos, articulaciones y lesiones'),
('Neurologia', 'Especialidad del sistema nervioso'),
('Oftalmologia', 'Especialidad de los ojos y la vision');

INSERT INTO Control_Medicos (NombreCompleto, CedulaProfesional, EspecialidadId, Telefono, CorreoElectronico, HorarioAtencion, Estatus) VALUES
('Dr. Juan Perez Gomez', 'CED-12345678', 3, '555-0101', 'juan.perez@clinica.com', 
'{"lunes":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"martes":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"miercoles":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"jueves":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"viernes":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"sabado":{"trabaja":false,"inicio":"","fin":""},"domingo":{"trabaja":false,"inicio":"","fin":""}}', 
1),

('Dra. Maria Garcia Lopez', 'CED-87654321', 2, '555-0102', 'maria.garcia@clinica.com',
'{"lunes":{"trabaja":true,"inicio":"10:00","fin":"18:00"},"martes":{"trabaja":true,"inicio":"10:00","fin":"18:00"},"miercoles":{"trabaja":false,"inicio":"","fin":""},"jueves":{"trabaja":true,"inicio":"10:00","fin":"18:00"},"viernes":{"trabaja":true,"inicio":"10:00","fin":"18:00"},"sabado":{"trabaja":true,"inicio":"09:00","fin":"13:00"},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1),

('Dr. Carlos Rodriguez Silva', 'CED-11223344', 1, '555-0103', 'carlos.rodriguez@clinica.com',
'{"lunes":{"trabaja":true,"inicio":"08:00","fin":"16:00"},"martes":{"trabaja":true,"inicio":"08:00","fin":"16:00"},"miercoles":{"trabaja":true,"inicio":"08:00","fin":"16:00"},"jueves":{"trabaja":true,"inicio":"08:00","fin":"16:00"},"viernes":{"trabaja":true,"inicio":"08:00","fin":"16:00"},"sabado":{"trabaja":false,"inicio":"","fin":""},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1),

('Dra. Ana Martinez Torres', 'CED-55667788', 4, '555-0104', 'ana.martinez@clinica.com',
'{"lunes":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"martes":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"miercoles":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"jueves":{"trabaja":false,"inicio":"","fin":""},"viernes":{"trabaja":true,"inicio":"09:00","fin":"17:00"},"sabado":{"trabaja":false,"inicio":"","fin":""},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1),

('Dra. Luis Hernandez Ruiz', 'CED-99887766', 5, '555-0105', 'luis.hernandez@clinica.com',
'{"lunes":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"martes":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"miercoles":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"jueves":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"viernes":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"sabado":{"trabaja":false,"inicio":"","fin":""},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1),

('Dr. Roberto Sanchez Vega', 'CED-44556677', 6, '555-0106', 'roberto.sanchez@clinica.com',
'{"lunes":{"trabaja":false,"inicio":"","fin":""},"martes":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"miercoles":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"jueves":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"viernes":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"sabado":{"trabaja":true,"inicio":"08:00","fin":"12:00"},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1);

INSERT INTO Control_Pacientes (NombreCompleto, CURP, FechaNacimiento, Sexo, Telefono, CorreoElectronico, Direccion, ContactoEmergencia, TelefonoEmergencia, Alergias, AntecedentesMedicos, Estatus) VALUES
('Pedro Lopez Ramirez', 'LOPR850315HDFLPD01', '1985-03-15', 'M', '555-1001', 'pedro.lopez@email.com', 'Calle Reforma 123, Col. Centro', 'Martha Ramirez (Esposa)', '555-1002', 'Penicilina', 'Diabetes tipo 2', 1),
('Laura Mendoza Cruz', 'MECL920720MDFLNR02', '1992-07-20', 'F', '555-1003', 'laura.mendoza@email.com', 'Av. Juarez 456, Col. Jardines', 'Jose Mendoza (Padre)', '555-1004', 'Ninguna', 'Saludable', 1),
('Jorge Diaz Santos', 'DISJ780910HDFLNR03', '1978-09-10', 'M', '555-1005', 'jorge.diaz@email.com', 'Calle Hidalgo 789, Col. Primavera', 'Rosa Santos (Madre)', '555-1006', 'Aspirina', 'Hipertension arterial', 1),
('Carmen Flores Ortiz', 'FLOC881205MDFLRR04', '1988-12-05', 'F', '555-1007', 'carmen.flores@email.com', 'Av. Insurgentes 321, Col. Moderna', 'Luis Ortiz (Esposo)', '555-1008', 'Ninguna', 'Ninguno', 1),
('Miguel Torres Vega', 'TOVM950425HDFLRG05', '1995-04-25', 'M', '555-1009', 'miguel.torres@email.com', 'Calle Morelos 654, Col. Lomas', 'Ana Vega (Madre)', '555-1010', 'Lactosa', 'Asma leve', 1),
('Rosa Silva Martinez', 'SIMR880815MDFLRS06', '1988-08-15', 'F', '555-1011', 'rosa.silva@email.com', 'Calle Zaragoza 234, Col. Progreso', 'Pedro Silva (Hermano)', '555-1012', 'Polen', 'Rinitis alergica', 1),
('Alberto Vargas Cruz', 'VACA900512HDFLRR07', '1990-05-12', 'M', '555-1013', 'alberto.vargas@email.com', 'Av. Libertad 567, Col. Vista Hermosa', 'Gloria Cruz (Madre)', '555-1014', 'Ninguna', 'Fractura de tibia (2018)', 1),
('Diana Ruiz Gomez', 'RUGD931128MDFLMN08', '1993-11-28', 'F', '555-1015', 'diana.ruiz@email.com', 'Calle Aldama 890, Col. Insurgentes', 'Carlos Gomez (Padre)', '555-1016', 'Mariscos', 'Gastritis cronica', 1),
('Fernando Ortiz Leon', 'OILF870620HDFLRN09', '1987-06-20', 'M', '555-1017', 'fernando.ortiz@email.com', 'Av. Revolucion 432, Col. Santa Fe', 'Monica Leon (Esposa)', '555-1018', 'Ninguna', 'Colesterol alto', 1),
('Veronica Campos Soto', 'CASV910305MDFLTR10', '1991-03-05', 'F', '555-1019', 'veronica.campos@email.com', 'Calle Bravo 765, Col. Del Valle', 'Ricardo Soto (Hermano)', '555-1020', 'Ibuprofeno', 'Migraña cronica', 1);

INSERT INTO Gestor_Tarifas (DescripcionServicio, CostoBase, EspecialidadId, Estatus) VALUES
('Consulta General', 350.00, 1, 1),
('Consulta Pediatrica', 400.00, 2, 1),
('Consulta Cardiologia', 550.00, 3, 1),
('Consulta Dermatologia', 450.00, 4, 1),
('Consulta Ginecologia', 500.00, 5, 1),
('Consulta Traumatologia', 500.00, 6, 1),
('Electrocardiograma', 300.00, 3, 1),
('Vacunacion infantil', 250.00, 2, 1),
('Ultrasonido', 600.00, 5, 1),
('Tratamiento dermatologico', 800.00, 4, 1),
('Radiografia', 400.00, 6, 1),
('Analisis de laboratorio', 200.00, 1, 1);


-- Usuarios del sistema (admin: admin/admin, otros: usuario/12345)
INSERT INTO Usuarios_Sistema (Usuario, Contrasena, Rol, IdMedico, IdPaciente, NombreCompleto, Telefono, CorreoElectronico, Activo) VALUES
-- Administrador y recepcionistas
('admin', 'admin', 'Admin', NULL, NULL, 'Administrador Sistema', '555-0001', 'admin@clinica.com', 1),
('recepcion1', '12345', 'Recepcionista', NULL, NULL, 'Sofia Ramirez Luna', '555-0002', 'sofia.ramirez@clinica.com', 1),
('recepcion2', '12345', 'Recepcionista', NULL, NULL, 'Patricia Morales Diaz', '555-0003', 'patricia.morales@clinica.com', 1),
('recepcion3', '12345', 'Recepcionista', NULL, NULL, 'Gabriela Castro Flores', '555-0004', 'gabriela.castro@clinica.com', 1),

-- Medicos
('dr.juan', '12345', 'Medico', 1, NULL, 'Dr. Juan Perez Gomez', '555-0101', 'juan.perez@clinica.com', 1),
('dra.maria', '12345', 'Medico', 2, NULL, 'Dra. Maria Garcia Lopez', '555-0102', 'maria.garcia@clinica.com', 1),
('dr.carlos', '12345', 'Medico', 3, NULL, 'Dr. Carlos Rodriguez Silva', '555-0103', 'carlos.rodriguez@clinica.com', 1),
('dra.ana', '12345', 'Medico', 4, NULL, 'Dra. Ana Martinez Torres', '555-0104', 'ana.martinez@clinica.com', 1),
('dra.luis', '12345', 'Medico', 5, NULL, 'Dra. Luis Hernandez Ruiz', '555-0105', 'luis.hernandez@clinica.com', 1),
('dr.roberto', '12345', 'Medico', 6, NULL, 'Dr. Roberto Sanchez Vega', '555-0106', 'roberto.sanchez@clinica.com', 1),

-- Pacientes (vinculados a Control_Pacientes)
('paciente1', '12345', 'Paciente', NULL, 1, 'Pedro Lopez Ramirez', '555-1001', 'pedro.lopez@email.com', 1),
('paciente2', '12345', 'Paciente', NULL, 2, 'Laura Mendoza Cruz', '555-1003', 'laura.mendoza@email.com', 1),
('paciente3', '12345', 'Paciente', NULL, 3, 'Jorge Diaz Santos', '555-1005', 'jorge.diaz@email.com', 1),
('paciente4', '12345', 'Paciente', NULL, 4, 'Carmen Flores Ortiz', '555-1007', 'carmen.flores@email.com', 1),
('paciente5', '12345', 'Paciente', NULL, 5, 'Miguel Torres Vega', '555-1009', 'miguel.torres@email.com', 1),
('paciente6', '12345', 'Paciente', NULL, 6, 'Rosa Silva Martinez', '555-1011', 'rosa.silva@email.com', 1),
('paciente7', '12345', 'Paciente', NULL, 7, 'Alberto Vargas Cruz', '555-1013', 'alberto.vargas@email.com', 1),
('paciente8', '12345', 'Paciente', NULL, 8, 'Diana Ruiz Gomez', '555-1015', 'diana.ruiz@email.com', 1);

-- Asignaciones recepcionista-medico (muchos a muchos)
INSERT INTO Recepcionista_Medico (IdRecepcionista, IdMedico) VALUES
(2, 1), (2, 2), (3, 3), (3, 4), (4, 5), (4, 6);

-- ================================================================
-- CITAS Y PAGOS PARA DASHBOARD - DICIEMBRE 2025 
-- Semana actual: 25 Nov - 1 Dic 2025
-- ================================================================

-- CITAS COMPLETADAS (SEMANA ACTUAL)
INSERT INTO Control_Agenda (IdPaciente, IdMedico, FechaCita, MotivoConsulta, EstadoCita, Observaciones) VALUES
-- LUNES 25 Nov
(1, 1, '2025-11-25 10:00:00', 'Control cardiológico', 'Completada', NULL),
(2, 2, '2025-11-25 12:00:00', 'Consulta pediátrica', 'Completada', NULL),
(3, 3, '2025-11-25 14:30:00', 'Chequeo general', 'Completada', NULL),
-- MARTES 26 Nov
(4, 4, '2025-11-26 09:00:00', 'Consulta dermatológica', 'Completada', NULL),
(5, 5, '2025-11-26 11:00:00', 'Control ginecológico', 'Completada', NULL),
(6, 6, '2025-11-26 15:00:00', 'Revisión traumatología', 'Completada', NULL),
(7, 1, '2025-11-26 16:30:00', 'Seguimiento cardiología', 'Completada', NULL),
-- MIÉRCOLES 27 Nov
(8, 2, '2025-11-27 08:30:00', 'Vacunación infantil', 'Completada', NULL),
(1, 3, '2025-11-27 10:00:00', 'Consulta general', 'Completada', NULL),
(2, 4, '2025-11-27 12:00:00', 'Tratamiento dermatológico', 'Completada', NULL),
(3, 5, '2025-11-27 14:00:00', 'Ultrasonido', 'Completada', NULL),
(4, 6, '2025-11-27 16:00:00', 'Radiografía', 'Completada', NULL),
-- JUEVES 28 Nov
(5, 1, '2025-11-28 09:00:00', 'Electrocardiograma', 'Completada', NULL),
(6, 2, '2025-11-28 10:30:00', 'Control pediátrico', 'Completada', NULL),
(7, 3, '2025-11-28 12:00:00', 'Chequeo general', 'Completada', NULL),
(8, 4, '2025-11-28 13:30:00', 'Consulta dermatología', 'Completada', NULL),
(1, 5, '2025-11-28 15:00:00', 'Control ginecológico', 'Completada', NULL),
(2, 6, '2025-11-28 16:30:00', 'Consulta traumatología', 'Completada', NULL),
-- VIERNES 29 Nov (día pico)
(3, 1, '2025-11-29 08:00:00', 'Seguimiento cardiología', 'Completada', NULL),
(4, 2, '2025-11-29 09:30:00', 'Consulta pediátrica', 'Completada', NULL),
(5, 3, '2025-11-29 11:00:00', 'Análisis de laboratorio', 'Completada', NULL),
(6, 4, '2025-11-29 12:30:00', 'Tratamiento dermatológico', 'Completada', NULL),
(7, 5, '2025-11-29 14:00:00', 'Control ginecológico', 'Completada', NULL),
(8, 6, '2025-11-29 15:30:00', 'Consulta traumatología', 'Completada', NULL),
(1, 1, '2025-11-29 17:00:00', 'Electrocardiograma', 'Completada', NULL),
-- SÁBADO 30 Nov  
(2, 2, '2025-11-30 09:00:00', 'Vacunación', 'Completada', NULL),
(3, 3, '2025-11-30 10:30:00', 'Consulta general', 'Completada', NULL),
(4, 4, '2025-11-30 11:45:00', 'Consulta dermatología', 'Completada', NULL),
(5, 5, '2025-11-30 12:30:00', 'Control ginecológico', 'Completada', NULL),
-- DOMINGO 1 Dic (HOY)
(6, 3, '2025-12-01 10:00:00', 'Urgencia - Consulta general', 'Completada', NULL),
(7, 3, '2025-12-01 11:30:00', 'Consulta general', 'Completada', NULL),
-- CITAS PROGRAMADAS FUTURAS
(1, 1, '2025-12-02 10:00:00', 'Control cardiológico mensual', 'Programada', 'Paciente con diabetes'),
(2, 2, '2025-12-02 11:00:00', 'Revisión pediátrica', 'Programada', 'Vacunas pendientes'),
(3, 3, '2025-12-03 09:00:00', 'Consulta general', 'Programada', NULL),
(4, 4, '2025-12-03 14:00:00', 'Seguimiento dermatológico', 'Programada', 'Tratamiento acné'),
(5, 5, '2025-12-04 15:00:00', 'Control ginecológico', 'Programada', NULL),
(6, 6, '2025-12-04 10:00:00', 'Revisión traumatología', 'Programada', 'Fractura consolidando'),
(7, 1, '2025-12-05 11:00:00', 'Seguimiento cardiología', 'Programada', NULL),
(8, 2, '2025-12-05 12:00:00', 'Control pediátrico', 'Programada', NULL),
(1, 3, '2025-12-06 09:00:00', 'Chequeo general', 'Programada', NULL),
(2, 4, '2025-12-06 16:00:00', 'Consulta dermatología', 'Programada', NULL),
(3, 5, '2025-12-09 10:00:00', 'Control ginecológico', 'Programada', NULL),
(4, 6, '2025-12-09 14:00:00', 'Consulta traumatología', 'Programada', NULL),
(5, 1, '2025-12-10 09:00:00', 'Seguimiento cardiología', 'Programada', NULL),
(6, 2, '2025-12-10 11:00:00', 'Consulta pediátrica', 'Programada', NULL);

-- PAGOS DE LA SEMANA

-- LUNES 25 Nov - 3 pagos
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(1, 1, 500.00, 'Efectivo', '2025-11-25 10:30:00', 'PAG-001', 'Pagado', 2),
(2, 2, 750.00, 'Tarjeta', '2025-11-25 12:15:00', 'PAG-002', 'Pagado', 2),
(3, 3, 600.00, 'Transferencia', '2025-11-25 14:45:00', 'REF-001', 'Pagado', 2);

-- MARTES 26 Nov - 4 pagos
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(4, 4, 450.00, 'Efectivo', '2025-11-26 09:00:00', 'PAG-003', 'Pagado', 3),
(5, 5, 800.00, 'Tarjeta', '2025-11-26 11:30:00', 'PAG-004', 'Pagado', 3),
(6, 6, 550.00, 'Efectivo', '2025-11-26 15:20:00', 'PAG-005', 'Pagado', 3),
(7, 7, 700.00, 'Transferencia', '2025-11-26 16:45:00', 'REF-002', 'Pagado', 2);

-- MIÉRCOLES 27 Nov - 5 pagos
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(8, 8, 350.00, 'Efectivo', '2025-11-27 08:30:00', 'PAG-006', 'Pagado', 2),
(9, 1, 600.00, 'Tarjeta', '2025-11-27 10:15:00', 'PAG-007', 'Pagado', 3),
(10, 2, 900.00, 'Tarjeta', '2025-11-27 12:00:00', 'PAG-008', 'Pagado', 2),
(11, 3, 400.00, 'Efectivo', '2025-11-27 14:30:00', 'PAG-009', 'Pagado', 3),
(12, 4, 650.00, 'Transferencia', '2025-11-27 16:00:00', 'REF-003', 'Pagado', 2);

-- JUEVES 28 Nov - 6 pagos
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(13, 5, 500.00, 'Efectivo', '2025-11-28 09:00:00', 'PAG-010', 'Pagado', 3),
(14, 6, 750.00, 'Tarjeta', '2025-11-28 10:30:00', 'PAG-011', 'Pagado', 2),
(15, 7, 450.00, 'Efectivo', '2025-11-28 12:15:00', 'PAG-012', 'Pagado', 3),
(16, 8, 850.00, 'Transferencia', '2025-11-28 13:45:00', 'REF-004', 'Pagado', 2),
(17, 1, 600.00, 'Tarjeta', '2025-11-28 15:00:00', 'PAG-013', 'Pagado', 3),
(18, 2, 400.00, 'Efectivo', '2025-11-28 16:30:00', 'PAG-014', 'Pagado', 2);

-- VIERNES 29 Nov - 7 pagos (día pico)
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(19, 3, 550.00, 'Efectivo', '2025-11-29 08:00:00', 'PAG-015', 'Pagado', 2),
(20, 4, 700.00, 'Tarjeta', '2025-11-29 09:30:00', 'PAG-016', 'Pagado', 3),
(21, 5, 800.00, 'Tarjeta', '2025-11-29 11:00:00', 'PAG-017', 'Pagado', 2),
(22, 6, 450.00, 'Efectivo', '2025-11-29 12:30:00', 'PAG-018', 'Pagado', 3),
(23, 7, 650.00, 'Transferencia', '2025-11-29 14:00:00', 'REF-005', 'Pagado', 2),
(24, 8, 500.00, 'Efectivo', '2025-11-29 15:30:00', 'PAG-019', 'Pagado', 3),
(25, 1, 900.00, 'Tarjeta', '2025-11-29 17:00:00', 'PAG-020', 'Pagado', 2);

-- SÁBADO 30 Nov - 4 pagos
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(26, 2, 400.00, 'Efectivo', '2025-11-30 09:00:00', 'PAG-021', 'Pagado', 3),
(27, 3, 600.00, 'Tarjeta', '2025-11-30 10:30:00', 'PAG-022', 'Pagado', 2),
(28, 4, 750.00, 'Transferencia', '2025-11-30 11:45:00', 'REF-006', 'Pagado', 3),
(29, 5, 550.00, 'Efectivo', '2025-11-30 12:30:00', 'PAG-023', 'Pagado', 2);

-- DOMINGO 1 Dic (HOY) - 2 pagos
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago, IdUsuarioRecibe) VALUES
(30, 6, 500.00, 'Efectivo', '2025-12-01 10:00:00', 'PAG-024', 'Pagado', 2),
(31, 7, 800.00, 'Tarjeta', '2025-12-01 11:30:00', 'PAG-025', 'Pagado', 3);

-- ================================================================
-- DETALLES DE PAGOS (desglose de servicios por pago)
-- ================================================================
INSERT INTO Detalle_Pagos (IdPago, IdTarifa, Cantidad, PrecioUnitario, Subtotal, Descripcion) VALUES
-- Pago 1: Consulta cardiologia
(1, 3, 1, 550.00, 550.00, 'Consulta Cardiologia'),
-- Pago 2: Consulta pediatrica + vacunacion
(2, 2, 1, 400.00, 400.00, 'Consulta Pediatrica'),
(2, 8, 1, 250.00, 250.00, 'Vacunacion infantil'),
-- Pago 3: Consulta general + analisis
(3, 1, 1, 350.00, 350.00, 'Consulta General'),
(3, 12, 1, 200.00, 200.00, 'Analisis de laboratorio'),
-- Pago 4: Consulta dermatologia
(4, 4, 1, 450.00, 450.00, 'Consulta Dermatologia'),
-- Pago 5: Consulta ginecologia + ultrasonido
(5, 5, 1, 500.00, 500.00, 'Consulta Ginecologia'),
(5, 9, 1, 600.00, 600.00, 'Ultrasonido'),
-- Pago 6: Consulta traumatologia
(6, 6, 1, 500.00, 500.00, 'Consulta Traumatologia'),
-- Pago 7: Consulta cardiologia + electrocardiograma
(7, 3, 1, 550.00, 550.00, 'Consulta Cardiologia'),
(7, 7, 1, 300.00, 300.00, 'Electrocardiograma'),
-- Pago 8: Vacunacion
(8, 8, 1, 250.00, 250.00, 'Vacunacion infantil'),
-- Pago 9: Consulta general
(9, 1, 1, 350.00, 350.00, 'Consulta General'),
-- Pago 10: Tratamiento dermatologico
(10, 10, 1, 800.00, 800.00, 'Tratamiento dermatologico'),
-- Pago 11: Ultrasonido
(11, 9, 1, 600.00, 600.00, 'Ultrasonido'),
-- Pago 12: Radiografia
(12, 11, 1, 400.00, 400.00, 'Radiografia');

-- ================================================================
-- EXPEDIENTES CLINICOS (historial medico de consultas completadas)
-- ================================================================
INSERT INTO Expediente_Clinico (IdPaciente, IdMedico, IdCita, FechaConsulta, Sintomas, Diagnostico, Tratamiento, RecetaMedica, NotasAdicionales, ProximaCita) VALUES
-- Expedientes de citas completadas
(1, 1, 1, '2025-11-25 10:00:00', 'Dolor en el pecho, fatiga', 'Arritmia cardiaca leve', 'Reposo y medicacion', 'Metoprolol 50mg cada 12 horas', 'Paciente diabetico, monitorear glucosa', '2025-12-02 10:00:00'),
(2, 2, 2, '2025-11-25 12:00:00', 'Fiebre y tos', 'Infeccion respiratoria', 'Antibioticos y reposo', 'Amoxicilina 500mg cada 8 horas por 7 dias', 'Revisar en una semana', '2025-12-02 11:00:00'),
(3, 3, 3, '2025-11-25 14:30:00', 'Revision general', 'Paciente saludable', 'Continuar dieta balanceada', 'Multivitaminico diario', 'Hipertension controlada', NULL),
(4, 4, 4, '2025-11-26 09:00:00', 'Manchas en la piel', 'Dermatitis atopica', 'Crema hidratante y corticoides topicos', 'Hidrocortisona crema 1% dos veces al dia', 'Evitar jabones perfumados', '2025-12-03 14:00:00'),
(5, 5, 5, '2025-11-26 11:00:00', 'Control rutinario', 'Paciente sana', 'Continuar cuidados preventivos', 'Acido folico 400mcg diario', 'Resultados de papanicolau normales', '2025-12-04 15:00:00'),
(6, 6, 6, '2025-11-26 15:00:00', 'Dolor en rodilla derecha', 'Esguince de ligamento lateral', 'Vendaje elastico y antiinflamatorios', 'Ibuprofeno 400mg cada 8 horas', 'Usar muletas por 2 semanas', '2025-12-04 10:00:00'),
(7, 1, 7, '2025-11-26 16:30:00', 'Seguimiento post-tratamiento', 'Mejoria en arritmia', 'Continuar medicacion', 'Metoprolol 50mg cada 12 horas', 'Realizar electrocardiograma de control', '2025-12-05 11:00:00'),
(8, 2, 8, '2025-11-27 08:30:00', 'Vacunacion programada', 'Aplicacion de vacuna', 'Vacuna triple viral', 'N/A - Vacuna aplicada', 'Sin reacciones adversas', NULL),
(1, 3, 9, '2025-11-27 10:00:00', 'Malestar general', 'Gripe comun', 'Reposo e hidratacion', 'Paracetamol 500mg cada 6 horas', 'Evitar cambios de temperatura', NULL),
(2, 4, 10, '2025-11-27 12:00:00', 'Acne severo', 'Acne vulgar grado III', 'Tratamiento topico y oral', 'Isotretinoina 20mg diario, Clindamicina gel', 'Control mensual obligatorio', NULL);

-- ================================================================
-- BITACORA DE ACCESO (registro de actividades del sistema)
-- ================================================================
INSERT INTO Bitacora_Acceso (IdUsuario, TipoAccion, Modulo, DescripcionAccion, IdRegistroAfectado, DatosAnteriores, DatosNuevos, FechaHora, DireccionIP) VALUES
-- Logins del dia
(1, 'Login', 'Autenticacion', 'Inicio de sesion exitoso', NULL, NULL, NULL, '2025-12-01 08:00:00', '192.168.1.100'),
(2, 'Login', 'Autenticacion', 'Inicio de sesion exitoso', NULL, NULL, NULL, '2025-12-01 08:05:00', '192.168.1.101'),
(3, 'Login', 'Autenticacion', 'Inicio de sesion exitoso', NULL, NULL, NULL, '2025-12-01 08:10:00', '192.168.1.102'),
(5, 'Login', 'Autenticacion', 'Inicio de sesion exitoso', NULL, NULL, NULL, '2025-12-01 09:00:00', '192.168.1.103'),
-- Acciones de recepcionistas
(2, 'Insertar', 'Agenda', 'Nueva cita registrada', 30, NULL, '{"IdPaciente":6,"IdMedico":3,"FechaCita":"2025-12-01 10:00:00"}', '2025-12-01 09:30:00', '192.168.1.101'),
(2, 'Insertar', 'Pagos', 'Nuevo pago registrado', 24, NULL, '{"Monto":500.00,"MetodoPago":"Efectivo"}', '2025-12-01 10:15:00', '192.168.1.101'),
(3, 'Insertar', 'Agenda', 'Nueva cita registrada', 31, NULL, '{"IdPaciente":7,"IdMedico":3,"FechaCita":"2025-12-01 11:30:00"}', '2025-12-01 10:00:00', '192.168.1.102'),
(3, 'Insertar', 'Pagos', 'Nuevo pago registrado', 25, NULL, '{"Monto":800.00,"MetodoPago":"Tarjeta"}', '2025-12-01 11:45:00', '192.168.1.102'),
-- Acciones de administrador
(1, 'Ver', 'Dashboard', 'Consulta de estadisticas del sistema', NULL, NULL, NULL, '2025-12-01 08:30:00', '192.168.1.100'),
(1, 'Ver', 'Usuarios', 'Listado de usuarios del sistema', NULL, NULL, NULL, '2025-12-01 08:35:00', '192.168.1.100'),
(1, 'Editar', 'Usuarios', 'Actualizacion de datos de usuario', 2, '{"Telefono":"555-0002"}', '{"Telefono":"555-0022"}', '2025-12-01 08:40:00', '192.168.1.100'),
-- Acciones de medicos
(5, 'Ver', 'Agenda', 'Consulta de citas del dia', NULL, NULL, NULL, '2025-12-01 09:15:00', '192.168.1.103'),
(5, 'Insertar', 'Expedientes', 'Nuevo expediente clinico creado', 1, NULL, '{"IdPaciente":1,"Diagnostico":"Arritmia cardiaca leve"}', '2025-12-01 11:00:00', '192.168.1.103'),
(5, 'Ver', 'Pacientes', 'Consulta de historial de paciente', 1, NULL, NULL, '2025-12-01 09:45:00', '192.168.1.103'),
-- Logouts
(5, 'Logout', 'Autenticacion', 'Cierre de sesion', NULL, NULL, NULL, '2025-12-01 14:00:00', '192.168.1.103'),
(2, 'Logout', 'Autenticacion', 'Cierre de sesion', NULL, NULL, NULL, '2025-12-01 18:00:00', '192.168.1.101'),
(3, 'Logout', 'Autenticacion', 'Cierre de sesion', NULL, NULL, NULL, '2025-12-01 18:05:00', '192.168.1.102'),
(1, 'Logout', 'Autenticacion', 'Cierre de sesion', NULL, NULL, NULL, '2025-12-01 19:00:00', '192.168.1.100');

-- ================================================================
-- REPORTES GENERADOS
-- ================================================================
INSERT INTO Reportes (TipoReporte, FormatoReporte, IdPaciente, IdMedico, FechaGeneracion, RutaArchivo, Descripcion, IdUsuarioGenera) VALUES
('Citas', 'PDF', NULL, NULL, '2025-11-30 17:00:00', '/reportes/citas_noviembre_2025.pdf', 'Reporte de citas del mes de noviembre 2025', 1),
('Ingresos', 'Excel', NULL, NULL, '2025-11-30 17:30:00', '/reportes/ingresos_noviembre_2025.xlsx', 'Corte de caja mensual noviembre 2025', 1),
('Pacientes', 'PDF', NULL, NULL, '2025-11-28 10:00:00', '/reportes/listado_pacientes.pdf', 'Listado completo de pacientes activos', 2),
('Bitacora', 'PDF', NULL, NULL, '2025-11-29 18:00:00', '/reportes/bitacora_semanal.pdf', 'Bitacora de acceso semanal', 1),
('Citas', 'Excel', NULL, 1, '2025-11-27 16:00:00', '/reportes/citas_dr_juan.xlsx', 'Agenda del Dr. Juan Perez - Noviembre', 5),
('Pacientes', 'PDF', 1, NULL, '2025-11-26 14:00:00', '/reportes/expediente_pedro_lopez.pdf', 'Expediente clinico de Pedro Lopez Ramirez', 5);

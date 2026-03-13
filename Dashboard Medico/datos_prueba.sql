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

-- Medicos con sus especialidades y horarios
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

('Dr. Luis Hernandez Ruiz', 'CED-99887766', 5, '555-0105', 'luis.hernandez@clinica.com',
'{"lunes":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"martes":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"miercoles":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"jueves":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"viernes":{"trabaja":true,"inicio":"11:00","fin":"19:00"},"sabado":{"trabaja":false,"inicio":"","fin":""},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1),

('Dr. Roberto Sanchez Vega', 'CED-44556677', 6, '555-0106', 'roberto.sanchez@clinica.com',
'{"lunes":{"trabaja":false,"inicio":"","fin":""},"martes":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"miercoles":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"jueves":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"viernes":{"trabaja":true,"inicio":"08:00","fin":"14:00"},"sabado":{"trabaja":true,"inicio":"08:00","fin":"12:00"},"domingo":{"trabaja":false,"inicio":"","fin":""}}',
1);

-- Tarifas de servicios
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
INSERT INTO Usuarios_Sistema (Usuario, Contrasena, Rol, IdMedico, NombreCompleto, Telefono, CorreoElectronico, Activo) VALUES
('admin', 'admin', 'Admin', NULL, 'Administrador Sistema', '555-0001', 'admin@clinica.com', 1),
('recepcion1', '12345', 'Recepcionista', NULL, 'Sofia Ramirez Luna', '555-0002', 'sofia.ramirez@clinica.com', 1),
('recepcion2', '12345', 'Recepcionista', NULL, 'Patricia Morales Diaz', '555-0003', 'patricia.morales@clinica.com', 1),
('recepcion3', '12345', 'Recepcionista', NULL, 'Gabriela Castro Flores', '555-0004', 'gabriela.castro@clinica.com', 1),
('dr.juan', '12345', 'Medico', 1, 'Dr. Juan Perez Gomez', '555-0101', 'juan.perez@clinica.com', 1),
('dra.maria', '12345', 'Medico', 2, 'Dra. Maria Garcia Lopez', '555-0102', 'maria.garcia@clinica.com', 1),
('dr.carlos', '12345', 'Medico', 3, 'Dr. Carlos Rodriguez Silva', '555-0103', 'carlos.rodriguez@clinica.com', 1),
('dra.ana', '12345', 'Medico', 4, 'Dra. Ana Martinez Torres', '555-0104', 'ana.martinez@clinica.com', 1),
('dr.luis', '12345', 'Medico', 5, 'Dr. Luis Hernandez Ruiz', '555-0105', 'luis.hernandez@clinica.com', 1),
('dr.roberto', '12345', 'Medico', 6, 'Dr. Roberto Sanchez Vega', '555-0106', 'roberto.sanchez@clinica.com', 1);

-- Asignaciones recepcionista-medico (muchos a muchos)
INSERT INTO Recepcionista_Medico (IdRecepcionista, IdMedico) VALUES
(2, 1), (2, 2), (3, 3), (3, 4), (4, 5), (4, 6);

-- Pacientes de ejemplo
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

-- Citas de la agenda (noviembre 2025)
INSERT INTO Control_Agenda (IdPaciente, IdMedico, FechaCita, MotivoConsulta, EstadoCita, Observaciones) VALUES
-- Citas de hoy (25 de noviembre 2025)
(1, 1, '2025-11-25 10:00:00', 'Control de diabetes', 'Programada', 'Paciente debe traer estudios de glucosa'),
(2, 2, '2025-11-25 11:30:00', 'Vacunacion infantil', 'Programada', 'Segunda dosis de vacuna'),
(3, 3, '2025-11-25 15:00:00', 'Dolor de pecho', 'Programada', 'Requiere electrocardiograma'),

-- Citas de mañana (26 de noviembre)
(4, 4, '2025-11-26 09:00:00', 'Revision de piel', 'Programada', 'Manchas en la piel'),
(5, 5, '2025-11-26 12:00:00', 'Control prenatal', 'Programada', 'Segundo trimestre'),
(6, 6, '2025-11-26 14:30:00', 'Dolor de rodilla', 'Programada', 'Lesion jugando futbol'),

-- Citas de esta semana
(7, 1, '2025-11-27 09:30:00', 'Chequeo general', 'Programada', 'Revision anual'),
(8, 2, '2025-11-28 14:00:00', 'Consulta pediatrica', 'Programada', 'Control de crecimiento'),
(9, 3, '2025-11-28 10:30:00', 'Seguimient colesterol', 'Programada', 'Traer estudios de sangre'),
(10, 4, '2025-11-29 16:00:00', 'Tratamiento acne', 'Programada', 'Tercera sesion'),

-- Citas completadas (pasadas)
(1, 1, '2025-11-20 10:00:00', 'Consulta general', 'Completada', 'Se receto tratamiento'),
(2, 2, '2025-11-21 16:00:00', 'Revision pediatrica', 'Completada', 'Paciente saludable'),
(3, 3, '2025-11-22 11:00:00', 'Control hipertension', 'Completada', 'Ajuste de medicamento'),
(4, 5, '2025-11-23 13:00:00', 'Consulta ginecologica', 'Completada', 'Control prenatal realizado'),

-- Citas canceladas
(5, 4, '2025-11-24 09:00:00', 'Dermatologia', 'Cancelada', 'Paciente cancelo por motivos personales');

-- Pagos registrados
INSERT INTO Gestor_Pagos (IdCita, IdPaciente, Monto, MetodoPago, FechaPago, Referencia, EstatusPago) VALUES
-- Pagos de citas completadas
(11, 1, 350.00, 'Efectivo', '2025-11-20 10:30:00', NULL, 'Pagado'),
(12, 2, 400.00, 'Tarjeta', '2025-11-21 16:30:00', 'REF-TDC-001234', 'Pagado'),
(13, 3, 550.00, 'Transferencia', '2025-11-22 11:45:00', 'SPEI-987654321', 'Pagado'),
(14, 4, 500.00, 'Efectivo', '2025-11-23 13:30:00', NULL, 'Pagado'),

-- Pagos pendientes de hoy
(1, 1, 350.00, 'Efectivo', '2025-11-25 10:30:00', NULL, 'Pendiente'),
(2, 2, 400.00, 'Tarjeta', '2025-11-25 12:00:00', NULL, 'Pendiente'),
(3, 3, 850.00, 'Transferencia', '2025-11-25 15:45:00', NULL, 'Pendiente');

-- Expedientes clinicos (noviembre 2025)
INSERT INTO Expediente_Clinico (IdPaciente, IdMedico, IdCita, FechaConsulta, Sintomas, Diagnostico, Tratamiento, RecetaMedica, NotasAdicionales, ProximaCita) VALUES
-- De citas completadas
(1, 1, 11, '2025-11-20 10:00:00', 
'Dolor de cabeza constante, mareos frecuentes, vision borrosa', 
'Hipertension arterial descontrolada', 
'Control de presion arterial, dieta baja en sodio, ejercicio moderado', 
'Losartan 50mg 1 tableta cada 12 horas, Hidroclorotiazida 25mg 1 tableta al dia',
'Paciente debe regresar en 15 dias para control. Evitar estres y alimentos procesados',
'2025-12-05 10:00:00'),

(2, 2, 12, '2025-11-21 16:00:00',
'Fiebre de 38.5 grados, tos con flema, dolor de garganta',
'Infeccion respiratoria aguda',
'Reposo, abundantes liquidos, antibiotico por 7 dias',
'Amoxicilina 500mg cada 8 horas por 7 dias, Paracetamol 500mg cada 6 horas si hay fiebre',
'Madre informa que el niño asiste a guarderia. Evitar cambios bruscos de temperatura',
'2025-11-28 16:00:00'),

(3, 3, 13, '2025-11-22 11:00:00',
'Dolor en el pecho lado izquierdo, palpitaciones, falta de aire al subir escaleras',
'Arritmia cardiaca leve, posible angina de pecho',
'Electrocardiograma de control, reducir consumo de cafeina y tabaco',
'Atenolol 25mg 1 tableta al dia, Aspirina 100mg 1 tableta al dia',
'Paciente fumador activo. Se recomienda programa para dejar de fumar',
'2025-12-06 11:00:00'),

(4, 5, 14, '2025-11-23 13:00:00',
'Nauseas matutinas, fatiga, sensibilidad en senos',
'Embarazo de 10 semanas confirmado',
'Suplemento de acido folico, dieta balanceada, reposo moderado',
'Acido folico 400mcg 1 tableta al dia, Complejo vitaminico prenatal 1 capsula al dia',
'Primer embarazo. Paciente en buen estado general. Ultrasonido programado',
'2025-12-07 15:30:00'),

-- Expedientes sin cita vinculada (urgencias)
(5, 6, NULL, '2025-11-19 08:00:00',
'Dolor intenso en rodilla derecha al caminar, inflamacion visible',
'Esguince de ligamento colateral medial grado 2',
'Reposo de rodilla, aplicar hielo 3 veces al dia, vendaje compresivo',
'Ibuprofeno 400mg cada 8 horas por 5 dias, Complejo B inyectable 3 aplicaciones',
'Lesion ocurrida jugando futbol. Se recomienda fisioterapia en 2 semanas',
'2025-12-03 09:00:00'),

(6, 1, NULL, '2025-11-18 14:00:00',
'Tos seca persistente, dolor de pecho al toser, fiebre leve',
'Bronquitis aguda',
'Reposo, mucoliticos, abundantes liquidos calientes',
'Ambroxol jarabe 15ml cada 8 horas, Dextrometorfano 10mg cada 12 horas',
'Paciente con antecedente de tabaquismo. Recomendar dejar de fumar',
'2025-12-02 10:00:00');

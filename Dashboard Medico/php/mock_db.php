<?php
// MockMysqli implementation to simulate a database connection for a portfolio without a real database.

class MockResult {
    public $num_rows = 1; 
    private $fetched_rows = 0;
    private $max_rows = 8; // Simulate 8 rows for tables

    public function fetch_assoc() {
        if ($this->fetched_rows >= $this->max_rows) {
            return false; // Stop the loop
        }
        $this->fetched_rows++;

        $nombres = ['Juan', 'María', 'José', 'Ana', 'Luis', 'Carmen', 'Carlos', 'Laura', 'Pedro', 'Sofía'];
        $apellidos = ['Pérez', 'García', 'Martínez', 'López', 'González', 'Rodríguez', 'Fernández', 'Gómez'];
        $especialidades = ['Medicina General', 'Cardiología', 'Pediatría', 'Ginecología', 'Dermatología', 'Nutrición'];
        $servicios = ['Consulta General', 'Revisión Anual', 'Electrocardiograma', 'Ultrasonido', 'Limpieza', 'Terapia'];
        $estadosCita = ['Programada', 'Completada', 'Cancelada'];
        $estadosPago = ['Pagado', 'Pendiente'];
        
        $nombre = $nombres[array_rand($nombres)];
        $apellido = $apellidos[array_rand($apellidos)];
        
        // Ensure first fetch (used by login) always returns the admin credentials accurately
        if ($this->fetched_rows === 1) {
            return [
                'total' => rand(30, 150), // For stats
                'IdUsuario' => 1,
                'Usuario' => 'admin',
                'Rol' => 'Admin',
                'IdMedico' => 1,
                'IdPaciente' => 1,
                'NombreCompleto' => 'Administrador (Demo)',
                'Activo' => 1,
                
                // Typical patient/medical columns
                'Id' => 1,
                'id' => 1,
                'Nombre' => $nombre,
                'nombre' => $nombre,
                'Apellidos' => $apellido,
                'Especialidad' => $especialidades[array_rand($especialidades)],
                'Telefono' => '55' . rand(10000000, 99999999),
                'Email' => strtolower($nombre) . '@ejemplo.com',
                'Estatus' => 1,
                'FechaRegistro' => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
                'Servicio' => $servicios[array_rand($servicios)],
                'Monto' => rand(3, 15) * 100,
                'EstatusPago' => $estadosPago[array_rand($estadosPago)],
                'EstadoCita' => $estadosCita[array_rand($estadosCita)],
                'fecha' => date('Y-m-d'),
                'Fecha' => date('Y-m-d'),
                'Hora' => rand(8, 18) . ':00:00'
            ];
        }

        // Return random data for subsequent fetches
        return [
            'total' => rand(30, 150),
            'IdUsuario' => rand(2, 20),
            'Usuario' => strtolower($nombre),
            'Rol' => (rand(0, 1) ? 'Medico' : 'Paciente'),
            'IdMedico' => rand(2, 20),
            'IdPaciente' => rand(2, 20),
            'NombreCompleto' => $nombre . ' ' . $apellido,
            'Activo' => 1,
            
            'Id' => rand(2, 100),
            'id' => rand(2, 100),
            'Nombre' => $nombre,
            'nombre' => $nombre,
            'Apellidos' => $apellido,
            'Especialidad' => $especialidades[array_rand($especialidades)],
            'Telefono' => '55' . rand(10000000, 99999999),
            'Email' => strtolower($nombre) . strtolower($apellido) . rand(1, 99) . '@ejemplo.com',
            'Estatus' => 1,
            'FechaRegistro' => date('Y-m-d', strtotime('-' . rand(1, 30) . ' days')),
            'Servicio' => $servicios[array_rand($servicios)],
            'Monto' => rand(3, 15) * 100,
            'EstatusPago' => $estadosPago[array_rand($estadosPago)],
            'EstadoCita' => $estadosCita[array_rand($estadosCita)],
            'fecha' => date('Y-m-d', strtotime('+' . rand(0, 7) . ' days')),
            'Fecha' => date('Y-m-d', strtotime('+' . rand(0, 7) . ' days')),
            'Hora' => rand(8, 18) . ':00:00'
        ];
    }

    public function fetch_all($mode = MYSQLI_ASSOC) {
        $rows = [];
        while ($row = $this->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }
}

class MockStmt {
    public $insert_id = 1;
    public $affected_rows = 1;

    public function bind_param(...$args) {
        return true;
    }

    public function execute() {
        return true;
    }

    public function get_result() {
        return new MockResult();
    }

    public function close() {
        return true;
    }
}

class MockMysqli {
    public $connect_error = null;
    public $insert_id = 1;
    public $affected_rows = 1;

    public function __construct($host = null, $user = null, $password = null, $database = null) {
        // Do nothing! We're mocking!
    }

    public function set_charset($charset) {
        return true;
    }

    public function prepare($query) {
        return new MockStmt();
    }

    public function query($query) {
        // Just return a MockResult for direct queries
        return new MockResult();
    }

    public function close() {
        return true;
    }

    public function begin_transaction() {
        return true;
    }

    public function commit() {
        return true;
    }

    public function rollback() {
        return true;
    }
}
?>

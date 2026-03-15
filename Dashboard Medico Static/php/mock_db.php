<?php
// MockMysqli implementation to simulate a database connection for a portfolio without a real database.

class MockResult {
    public $num_rows = 1; // Simulate 1 row so login validation strictly passes
    private $fetched_rows = 0;

    public function fetch_assoc() {
        if ($this->fetched_rows >= $this->num_rows) {
            return false; // Stop the loop
        }
        $this->fetched_rows++;

        // Return dummy data common across queries
        return [
            'total' => rand(10, 100), // For stats
            'IdUsuario' => 1,
            'Usuario' => 'admin',
            'Rol' => 'Admin',
            'IdMedico' => 1,
            'IdPaciente' => 1,
            'NombreCompleto' => 'Administrador (Demo)',
            'Activo' => 1,
            
            // Typical patient/medical columns
            'Id' => rand(1, 100),
            'id' => rand(1, 100),
            'Nombre' => 'Nombre Prueba',
            'nombre' => 'Nombre Prueba',
            'Apellidos' => 'Apellido Prueba',
            'Especialidad' => 'General',
            'Telefono' => '555-5555',
            'Email' => 'prueba@ejemplo.com',
            'Estatus' => 1,
            'FechaRegistro' => date('Y-m-d'),
            'Servicio' => 'Consulta',
            'Monto' => 500,
            'EstatusPago' => 'Pagado',
            'EstadoCita' => 'Programada',
            'fecha' => date('Y-m-d'),
            'Fecha' => date('Y-m-d'),
            'Hora' => '10:00:00'
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

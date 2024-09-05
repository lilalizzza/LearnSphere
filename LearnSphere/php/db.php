<?php
// Configuración de la base de datos
$servername = "localhost";
$username = "root"; // Cambia esto si tu usuario es diferente
$password = ""; // Cambia esto si tu contraseña es diferente
$dbname = "alumnos";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Manejo del registro de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['full_name'])) {
    $full_name = $conn->real_escape_string($_POST['full_name']);
    $birthdate = $conn->real_escape_string($_POST['birthdate']);
    $school_year = $conn->real_escape_string($_POST['school_year']);
    $user = $conn->real_escape_string($_POST['user']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $avatar = $conn->real_escape_string($_POST['avatar']);

    $sql = "INSERT INTO usuarios (full_name, birthdate, school_year, user, password, avatar) 
            VALUES ('$full_name', '$birthdate', '$school_year', '$user', '$password', '$avatar')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. <a href='../templates/login.html'>Iniciar sesión</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Manejo del login de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['full_name'])) {
    $user = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT password FROM usuarios WHERE user='$user'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Redirigir al dashboard
            header("Location: ../templates/dashboard.html");
            exit();
        } else {
            echo "Credenciales incorrectas. <a href='../templates/login.html'>Intentar de nuevo</a>";
        }
    } else {
        echo "Usuario no encontrado. <a href='../templates/login.html'>Intentar de nuevo</a>";
    }
}

$conn->close();
?>

<?php  
    class UserGateway
    {
        private PDO $conn;
        public function __construct(Database $database)
        {
            $this->conn = $database->getConnection();
        }

        public function getAall(): array
        {
            $sql = "SELECT * FROM user_final";

            $stmt = $this -> conn -> query($sql);

            $data = [];

            while($row = $stmt -> fetch(PDO::FETCH_ASSOC)) 
            {
                $data[] = $row;
            }

            return $data;
        }

        public function create(array $data) : string
        {
            $sql = "INSERT INTO user_final (username, email, password)
                    VALUES (:username, :email, :password)";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindValue(":username", $data["username"], PDO::PARAM_STR);
            $stmt -> bindValue(":email", $data["email"], PDO::PARAM_STR);
            $stmt -> bindValue(":password", $data["password"], PDO::PARAM_STR);

            $stmt -> execute();

            return $this -> conn -> lastInsertId();
        }

        public function get(string $id) : array | false
        {
            $sql = "SELECT * FROM user_final WHERE id = :id";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindValue(":id", $id, PDO::PARAM_INT);

            $stmt -> execute();

            $data = $stmt -> fetch(PDO::FETCH_ASSOC);

            // if($data !== false)
            // {
            //     $data["accept_conditions"] = (bool) $data["accept_conditions"];
            // }

            return $data;
        }

        public function update(array $current, array $new) : int
        {
            $sql = "UPDATE user_final
                    SET username = :username, email = :email, password = :password
                    WHERE id = :id";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindValue(":username", $new["username"] ?? $current["username"], PDO::PARAM_STR);
            $stmt -> bindValue(":email", $new["email"] ?? $current["email"], PDO::PARAM_STR);
            $stmt -> bindValue(":password", $new["password"] ?? $current["password"], PDO::PARAM_STR);

            $stmt -> bindValue(":id", $current["id"], PDO::PARAM_INT);

            $stmt -> execute();

            return $stmt -> rowCount();
        }

        public function delete(string $id): int
        {
            $sql = "DELETE FROM user_final
                    WHERE id = :id";

            $stmt = $this -> conn -> prepare($sql);

            $stmt -> bindValue(":id", $id, PDO::PARAM_INT);

            $stmt -> execute();

            return $stmt -> rowCount();
        }
    }
?>
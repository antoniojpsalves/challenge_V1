<?php

class TonyConnection {
    private $conn;

    public function __construct(string $dbname, string $host, string $user, string $password) {
        try {
            $this->conn = new PDO("mysql:dbname=" . $dbname . "; host=" . $host, $user, $password);
        } catch(PDOException $e) {
            echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
            exit();
        } catch(Exception $e) {
            echo "Erro genérico: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Função que recebe uma string nome e retorna os dados filtrados pelo nome
     */
    public function pesquisarNome(string $nome): string {
        $stmt = $this->conn->prepare("Select * from user where nome = :n");
        $stmt->bindValue(":n", $nome);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Função que recebe uma string cpf e retorna os dados filtrados pelo cpf
     */
    public function pesquisarCpf(string $cpf): string {
        $stmt = $this->conn->prepare("Select * from user where cpf = :c");
        $stmt->bindValue(":c", $cpf);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Função que recebe uma string nasc no formato '1999-06-04' e retorna os dados filtrados
     */
    public function pesquisarNasc(string $nasc): string {
        $stmt = $this->conn->prepare("Select * from user where data_nasc = :d");
        $stmt->bindValue(":d", $nasc);
        $stmt->execute();

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Função que recebe strings nome, cpf. nasc. E verifica se o cpf já foi cadastrado.
     */
    public function inserirDados(string $nome, string $cpf, $data_nasc): string {
        $ret = self::pesquisarCpf($cpf) ? true : false;
        if($ret){
            return $ret = "Cpf já cadastrado.";
        }

        $stmt = $this->conn->prepare("INSERT INTO user (nome, cpf, data_nasc) VALUES (:n, :c, :d)");
        $stmt->bindValue(":n", $nome);
        $stmt->bindValue(":c", $cpf);
        $stmt->bindValue(":d", $data_nasc);
        $stmt->execute();
        $ret = 'Dados salvos com sucesso';

        return $ret;
    }

    /**
     * Função que retorna o número de registros.
     */
    public function quantidadeCadastros(): string {
        $query = "SELECT * FROM user";
        $res = $this->conn->query($query);
        $res->execute();
        $ret = $res->fetchAll(PDO::FETCH_ASSOC);
        $rs = count($ret);
        return $rs;
    }

    /**
     * Função que retorna o total de anivesariantes do mês atual
     */
    public function mostrarAniverariantes(): array {
        $stmt = $this->conn->query("SELECT nome FROM user where Month(data_nasc) = Month(Now())");
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $ret;
    }
}
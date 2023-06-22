<?php

require_once('Perfil.php');

class Usuario {

    private $id;
    private $matricula;
    private $nome;
    private $login;
    private $senha;
    private $perfil;

     
    public function __construct(int $matricula=0, string $nome="", string $login="", string $senha="", Perfil $perfil=null, int $id=-1) {
        $this->id = $id;
        $this->nome = $nome;
        $this->matricula = $matricula;
        $this->login = $login;
        $this->senha = $senha;
        $this->perfil = $perfil;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function setMatricula(int $matricula) {
        $this->matricula = $matricula;
    }

    public function getMatricula() {
        return $this->matricula;
    }

    public function setNome(string $nome) {
        $this->nome = $nome;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setLogin(string $login) {
        $this->login = $login;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setSenha(string $senha) {
        $this->senha = $senha;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setPerfil(Perfil $perfil) {
        $this->perfil = $perfil;
    }

    public function getPerfil() {
        return $this->perfil;
    }



}



?>
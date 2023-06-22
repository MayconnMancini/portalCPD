<?php 

class StatusBalanca {

  private $id;
  private $descricao;

  public function __construct(string $descricao='', int $id=-1) {
      $this->id = $id;
      $this->descricao = $descricao;
  }

  public function setId(int $id) {
      $this->id = $id;
  }

  public function getId() {
      return $this->id;
  }

  public function setDescricao($descricao) {
      $this->descricao = $descricao;
  }

  public function getDescricao() {
      return $this->descricao;
  }
  
};
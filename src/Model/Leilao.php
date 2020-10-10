<?php

namespace Alura\Leilao\Model;

class Leilao
{
    /** @var Lance[] */
    private $lances;
    /** @var string */
    private $descricao;

    public function __construct(string $descricao)
    {
        $this->descricao = $descricao;
        $this->lances = [];
    }

    public function recebeLance(Lance $lance)
    {
        if (!empty($this->lances) && $this->lanceEhDoUltimoUsuario($lance)) {
            return;
        }

        $totalLancesUsuario = $this->quantidadeDeLancesPorUsuario($lance->getUsuario());

        if ($totalLancesUsuario >= 5) {
            return;
        }

        $this->lances[] = $lance;
    }

    /**
     * @return Lance[]
     */
    public function getLances(): array
    {
        return $this->lances;
    }

    private function lanceEhDoUltimoUsuario($lance)
    {
        $ultimoLance = $this->lances[count($this->lances) - 1];
        return $lance->getUsuario() === $ultimoLance->getUsuario();
    }

    private function quantidadeDeLancesPorUsuario($usuario)
    {
        return array_reduce(
            $this->lances,
            function (int $totalAcumulado, Lance $lanceAtual) use ($usuario) {
                if ($lanceAtual->getUsuario() === $usuario) {
                    return $totalAcumulado + 1;
                } else {
                    return $totalAcumulado;
                }
            },
            0
        );
    }
}

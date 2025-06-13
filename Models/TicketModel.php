<?php

class TicketModel extends Model
{
    private $tickets_table = "tickets";
    private $ticket_updates_table = "ticket_updates";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Obtém todos os chamados com detalhes do cliente e do funcionário atribuído.
     * @return array Um array de arrays associativos com os dados dos chamados.
     */
    public function getAllTicketsWithDetails(): array
    {
        $query = "SELECT
                    t.id,
                    t.service_type,
                    t.description,
                    t.location,
                    t.status,
                    t.created_at,
                    t.updated_at,
                    c.company_name AS client_company_name,
                    u_creator.name AS creator_name,
                    u_assigned.name AS assigned_to_name
                  FROM " . $this->tickets_table . " t
                  LEFT JOIN clients c ON t.client_id = c.id
                  LEFT JOIN users u_creator ON t.created_by = u_creator.id
                  LEFT JOIN employees e_assigned ON t.assigned_to = e_assigned.id
                  LEFT JOIN users u_assigned ON e_assigned.user_id = u_assigned.id
                  ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtém os chamados de uma empresa específica pelo ID da empresa.
     * @param int $empresaId O ID da empresa cujos chamados devem ser recuperados.
     * @return array Um array de arrays associativos com os dados dos chamados da empresa.
     */
    public function getTicketsByEmpresaId(int $empresaId): array
    {
        $query = "SELECT 
                    t.id,
                    t.service_type AS titulo,
                    t.status,
                    t.created_at AS data_abertura,
                    u.name AS funcionario_nome
                  FROM {$this->tickets_table} t
                  LEFT JOIN employees e ON t.assigned_to = e.id
                  LEFT JOIN users u ON e.user_id = u.id
                  WHERE t.client_id = :empresa_id
                  ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':empresa_id', $empresaId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

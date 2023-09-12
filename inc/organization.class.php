<?php

/**
 *
 */
class PluginMreportingOrganization extends PluginMreportingBaseclass
{

   function reportHbarTicketsByOrganization($config = array(), $area = false) {
      global $DB;

      $_SESSION['mreporting_selector']['reportHbarTicketsByOrganization'] = array('dateinterval');

      $datas = array();

      //Init delay value
      $this->sql_date = PluginMreportingCommon::getSQLDate("glpi_tickets.date",
                                                           $config['delay'],
                                                           $config['randname']);

     $query = "SELECT glpi_tickets.requesttypes_id,
           glpi_requesttypes.name,
           COUNT(glpi_tickets.id) as count
        FROM glpi_tickets
        INNER JOIN glpi_requesttypes
           ON glpi_requesttypes.id = glpi_tickets.requesttypes_id
        WHERE {$this->sql_date}
           AND glpi_tickets.entities_id IN ({$this->where_entities})
           AND glpi_tickets.is_deleted = '0'
        GROUP BY glpi_tickets.requesttypes_id
        ORDER BY glpi_requesttypes.name";
     $result = $DB->query($query);

     while ($ticket = $DB->fetchAssoc($result)) {
        $datas['datas'][$ticket['name']] = $ticket['count'];
        $datas['labels2'][$ticket['name']] = $ticket['name'];
     }


     return $datas;
    }
}

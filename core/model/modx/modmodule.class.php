<?php
/**
 * Represents a MODx module element.
 *
 * Modules are an extension to {@link modScript} that run in the manager
 * interface and can share properties with other {@link modElement} instances.
 *
 * @package modx
 */
class modModule extends modScript {
    function modModule(& $xpdo) {
        $this->__construct($xpdo);
    }
    function __construct(& $xpdo) {
        parent :: __construct($xpdo);
    }

    /**
     * Get the source content of this module.
     */
    function getContent($options = array()) {
        if (!is_string($this->_content) || $this->_content === '') {
            if (isset($options['content'])) {
                $this->_content = $options['content'];
            } else {
                $this->_content = $this->get('modulecode');
            }
        }
        return $this->_content;
    }

    /**
     * Set the source content of this plugin.
     */
    function setContent($content, $options = array()) {
        return $this->set('modulecode', $content);
    }

    /**
     * Gets a collection of dependencies for the module.
     *
     * @todo Figure out what to do with this nasty SQL structure for modules.
     * @return array A collection of modModuleDepObj instances.
     */
    function getDependencies() {
        $c = new xPDOCriteria($this->xpdo,'
            SELECT
                smd.id,
                COALESCE(ss.name,st.templatename,sv.name,sc.name,sp.name,sd.pagetitle) AS name,
                CASE smd.type
                    WHEN 10 THEN :chunk
                    WHEN 20 THEN :document
                    WHEN 30 THEN :plugin
                    WHEN 40 THEN :snippet
                    WHEN 50 THEN :template
                    WHEN 60 THEN :tv
                END AS type
            FROM '.$this->xpdo->getTableName('modModuleDepobj').' AS smd

                LEFT JOIN '.$this->xpdo->getTableName('modChunk').' AS sc
                ON sc.id = smd.resource
                AND smd.type = 10

                LEFT JOIN '.$this->xpdo->getTableName('modResource').' AS sd
                ON sd.id = smd.resource
                AND smd.type = 20

                LEFT JOIN '.$this->xpdo->getTableName('modPlugin').' AS sp
                ON sp.id = smd.resource
                AND smd.type = 30

                LEFT JOIN '.$this->xpdo->getTableName('modSnippet').' AS ss
                ON ss.id = smd.resource
                AND smd.type = 40

                LEFT JOIN '.$this->xpdo->getTableName('modTemplate').' AS st
                ON st.id = smd.resource
                AND smd.type = 50

                LEFT JOIN '.$this->xpdo->getTableName('modTemplateVar').' AS sv
                ON sv.id = smd.resource
                AND smd.type = 60

            WHERE
                smd.module = :id

            ORDER BY smd.type,name
        ',array(
            ':id' => $this->id,
            ':chunk' => 'Chunk',
            ':document' => 'Document',
            ':plugin' => 'Plugin',
            ':snippet' => 'Snippet',
            ':template' => 'Template',
            ':tv' => 'TV',
        ));
        return $this->xpdo->getCollection('modModuleDepobj',$c);
    }
}
?>
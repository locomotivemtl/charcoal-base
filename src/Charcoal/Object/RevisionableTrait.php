<?php

namespace Charcoal\Object;

use \InvalidArgumentException;

use \Charcoal\Object\ObjectRevision;

use \Charcoal\Loader\CollectionLoader;

/**
 *
 */
trait RevisionableTrait
{
    /**
     * @var bool $revisionEnabled
     */
    private $revisionEnabled = true;

    /**
     * @param boolean $enabled The (revision) enabled flag.
     * @return RevisionableInterface Chainable
     */
    public function setRevisionEnabled($enabled)
    {
        $this->revisionEnabled = !!$enabled;
        return $this;
    }

    /**
     * @return boolean
     */
    public function revisionEnabled()
    {
        return $this->revisionEnabled;
    }

    /**
     * This method can be overloaded in concrete implementation to provide a different (custom) ObjectRevision class.
     *
     * @return ObjectRevision
     */
    public function revisionObject()
    {
        $rev = $this->modelFactory()->create(ObjectRevision::class);
        return $rev;
    }

    /**
     * @return ObjectRevision
     * @see \Charcoal\Object\ObjectRevision::create_fromObject()
     */
    public function generateRevision()
    {
        $rev = $this->revisionObject();

        $rev->createFromObject($this);
        if (!empty($rev->dataDiff())) {
            $rev->save();
        }

        return $rev;
    }

    /**
     * @return ObjectRevision
     * @see \Charcoal\Object\ObejctRevision::lastObjectRevision
     */
    public function latestRevision()
    {
        $rev = $this->revisionObject();
        $rev = $rev->lastObjectRevision($this);

        return $rev;
    }

    /**
     * @param integer $revNum The revision number.
     * @return ObjectRevision
     * @see \Charcoal\Object\ObejctRevision::objectRevisionNum
     */
    public function revisionNum($revNum)
    {
        $revNum = (int)$revNum;
        $rev = $this->revisionObject();
        $rev = $rev->objectRevisionNum($this, $revNum);

        return $rev;
    }

    /**
     * Retrieves all revisions for the current objet
     *
     * @param callable $callback Optional object callback.
     * @return array
     */
    public function allRevisions(callable $callback = null)
    {
        $loader = new CollectionLoader([
            'logger'    => $this->logger,
            'factory'   => $this->modelFactory()
        ]);
        $loader->setModel($this->revisionObject());
        $loader->addFilter('target_type', $this->objType());
        $loader->addFilter('target_id', $this->id());
        $loader->addOrder('rev_ts', 'desc');
        if ($callback !== null) {
            $loader->setCallback($callback);
        }
        $revisions = $loader->load();

        return $revisions->objects();
    }

    /**
     * @param integer $revNum The revision number to revert to.
     * @throws InvalidArgumentException If revision number is invalid.
     * @return boolean Success / Failure.
     */
    public function revertToRevision($revNum)
    {
        $revNum = (int)$revNum;
        if (!$revNum) {
            throw new InvalidArgumentException(
                'Invalid revision number'
            );
        }

        $rev = $this->revisionNum($revNum);

        if (!$rev->id()) {
            return false;
        }
        $this->setData($rev->dataObj());
        $this->update();

        return true;
    }

    /**
     * A model factory must be provided on implementing classes.
     *
     * @return FactoryInterface
     */
    abstract protected function modelFactory();
}

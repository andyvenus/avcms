<?php
/**
 * User: Andy
 * Date: 02/07/15
 * Time: 11:16
 */

namespace AV\Form\RestoreDataHandler;

use AV\Form\FormHandler;

/**
 * Interface RestoreDataHandlerInterface
 * @package AV\Form\RestoreDataHandler
 *
 * Restore data to the form from a previous request. Only used when the form
 * is not considered submitted
 */
interface RestoreDataHandlerInterface
{
    /**
     * Restore the request data. Can restore and perform any actions on the
     * FormHandler as necessary. So use to restore the form data, form
     * errors and more
     *
     * @param FormHandler $formHandler
     * @param $request
     * @return array|null
     */
    public function restoreData(FormHandler $formHandler, $request);

    /**
     * Called when the form handler's handleRequest method is called and it
     * is determined that the form *was* submitted. This is your chance to save
     * the current request data for restoration
     *
     * @param FormHandler $formHandler
     * @param array $data
     * @param $request
     * @return
     */
    public function setRestorableData(FormHandler $formHandler, array $data, $request);

    /**
     * Called when the form handler's isValid method is called. Your chance to
     * collect the form's validation errors
     *
     * @param FormHandler $formHandler
     * @param $errors \AV\Form\FormError[]
     */
    public function setRestorableErrors(FormHandler $formHandler, array $errors);

    /**
     * If called, the form was valid
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    public function setValid(FormHandler $formHandler);

    /**
     * Check if the form was valid
     *
     * @param FormHandler $formHandler
     * @return bool
     */
    public function wasValid(FormHandler $formHandler);

    /**
     * Cancel restoring the data. Called when the view is rendered.
     *
     * @param FormHandler $formHandler
     * @return mixed
     */
    public function cancelRestore(FormHandler $formHandler);
}

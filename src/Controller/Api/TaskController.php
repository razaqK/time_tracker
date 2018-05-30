<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/24/18
 * Time: 6:07 PM
 */

namespace App\Controller\Api;

use App\Constants\ResponseCodes;
use App\Constants\ResponseMessages;
use App\Constants\Status;
use App\Entity\Task;
use App\Entity\Tracker;
use App\Traits\ApiResponse;
use App\Traits\Validator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TaskController
 * @package App\Controller\Api
 */
class TaskController extends Controller
{

    use ApiResponse, Validator;

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = $this->validateParameters($data, ['name' => 'required']);
        if (!$validator['status']) {
            return $this->sendError($validator['message'], ResponseCodes::INVALID_PARAM, 400,
                $validator['data']);
        }

        $data['description'] = !empty($data['description']) ? $data['description'] : null;
        $entityManager = $this->getDoctrine()->getManager();

        $task = new Task();
        $task->setName($data['name']);
        $task->setDescription($data['description']);

        $entityManager->persist($task);
        $entityManager->flush();

        return $this->sendSuccess($task);
    }

    public function fetch(Request $request)
    {
        $page = $request->get('page');
        $limit = $request->get('limit');
        $keyword = $request->get('keyword');
        $name = $request->get('name');
        $returnOne = $request->get('return');
        $status = $request->get('status');

        $validator = $this->validateParameters([
            'page' => $page,
            'limit' => $limit,
            'return' => $returnOne
        ], [
            'page' => 'integer',
            'limit' => 'integer',
            'return' => 'boolean'
        ]);
        if (!$validator['status']) {
            return $this->sendError($validator['message'], ResponseCodes::INVALID_PARAM, 400,
                $validator['data']);
        }

        $page = !empty($page) ? $page : 0;
        $limit = !empty($limit) ? $limit : 20;
        $keyword = !empty($keyword) ? $keyword : null;
        $name = !empty($name) ? $name : null;
        $returnOne = !empty($returnOne) ? $returnOne : false;
        $status = !empty($status) ? $status : null;

        $tasks = $this->getDoctrine()
            ->getRepository(Task::class)
            ->getAll($keyword, $page, $limit, $name, $status, $returnOne);

        if (!$tasks) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'record'), ResponseCodes::NOT_FOUND, 404);
        }

        return $this->sendSuccess($tasks);
    }

    public function edit(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $task = $entityManager
            ->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'task'), ResponseCodes::NOT_FOUND, 404);
        }

        $data = json_decode($request->getContent(), true);
        $task->setArrayToField($data, ['id']);
        $entityManager->flush();

        return $this->sendSuccess($task);
    }

    public function changeStatus(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Task $task */
        $task = $entityManager
            ->getRepository(Task::class)->find($id);

        if (!$task) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'task'), ResponseCodes::NOT_FOUND, 404);
        }

        $data = json_decode($request->getContent(), true);
        $validator = $this->validateParameters($data, [
            'status' => 'required|contains_list,' . Status::PENDING . ';'
                . Status::IN_PROGRESS . ';' . Status::COMPLETED . ';' . Status::PAUSE,
            'total_seconds' => 'required|integer']);
        if (!$validator['status']) {
            return $this->sendError($validator['message'], ResponseCodes::INVALID_PARAM, 400,
                $validator['data']);
        }

        $task->setStatus($data['status']);

        $tracker = $task->getTracker();
        if ($tracker) {
            switch ($data['status']) {
                case Status::PENDING:
                case Status::COMPLETED:
                case Status::PAUSE:
                    $tracker->setState(0);
                    $tracker->setTotalSeconds($data['total_seconds']);
                    break;
                case Status::IN_PROGRESS:
                    $tracker->setState(1);
                    $tracker->setTotalSeconds($data['total_seconds']);
                    break;
            }
        }else {
            $tracker = new Tracker();
            $tracker->setStartTime(new \DateTime());
            $tracker->setState(1);
            $tracker->setTask($task);
            $tracker->setTotalSeconds($data['total_seconds']);

            $entityManager->persist($tracker);
        }

        $entityManager->flush();

        return $this->sendSuccess($task->getId());
    }
}
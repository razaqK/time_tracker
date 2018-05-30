<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/24/18
 * Time: 6:11 PM
 */

namespace App\Controller\Api;

use App\Constants\ResponseCodes;
use App\Constants\ResponseMessages;
use App\Constants\Status;
use App\Entity\Task;
use App\Entity\Tracker;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Traits\ApiResponse;
use App\Traits\Validator;
use Symfony\Component\HttpFoundation\Request;

class TrackerController extends Controller
{
    use ApiResponse, Validator;

    public function start(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $validator = $this->validateParameters($data, ['task_id' => 'required|integer']);
        if (!$validator['status']) {
            return $this->sendError($validator['message'], ResponseCodes::INVALID_PARAM, 400,
                $validator['data']);
        }

        $entityManager = $this->getDoctrine()->getManager();

        /** @var Task $task */
        $task = $entityManager
            ->getRepository(Task::class)->find($data['task_id']);

        if (!$task) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'task'), ResponseCodes::NOT_FOUND, 404);
        }

        $tracker = $task->getTracker();
        if (!$tracker) {
            $tracker = new Tracker();
            $tracker->setStartTime(new \DateTime());
            $tracker->setState(1);
            $tracker->setTask($task);

            $entityManager->persist($tracker);
        }else {
            $tracker->setStartTime(new \DateTime());
            $tracker->setState(1);
        }

        $entityManager->flush();

        return $this->sendSuccess($tracker);
    }

    public function pause(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $validator = $this->validateParameters($data, ['total_seconds' => 'required|integer']);
        if (!$validator['status']) {
            return $this->sendError($validator['message'], ResponseCodes::INVALID_PARAM, 400,
                $validator['data']);
        }

        $entityManager = $this->getDoctrine()->getManager();

        /** @var Tracker $tracker */
        $tracker = $entityManager
            ->getRepository(Tracker::class)->find($id);

        if (!$tracker) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'tracker'), ResponseCodes::NOT_FOUND, 404);
        }

        $tracker->setState(0);
        $tracker->setTotalSeconds($data['total_seconds']);
        $task  = $tracker->getTask();
        $task->setStatus(Status::PAUSE);

        $entityManager->flush();
        return $this->sendSuccess($tracker);
    }

    public function resume(Request $request, $id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        /** @var Tracker $tracker */
        $tracker = $entityManager
            ->getRepository(Tracker::class)->find($id);

        if (!$tracker) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'tracker'), ResponseCodes::NOT_FOUND, 404);
        }

        $tracker->setState(1);

        $task  = $tracker->getTask();
        $task->setStatus(Status::IN_PROGRESS);

        $entityManager->flush();
        return $this->sendSuccess($tracker);
    }

    public function updateTime(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $validator = $this->validateParameters($data, ['total_seconds' => 'required|integer']);
        if (!$validator['status']) {
            return $this->sendError($validator['message'], ResponseCodes::INVALID_PARAM, 400,
                $validator['data']);
        }

        $entityManager = $this->getDoctrine()->getManager();

        /** @var Tracker $tracker */
        $tracker = $entityManager
            ->getRepository(Tracker::class)->find($id);

        if (!$tracker) {
            return $this->sendError(sprintf(ResponseMessages::NOT_FOUND, 'tracker'), ResponseCodes::NOT_FOUND, 404);
        }

        $tracker->setTotalSeconds($data['total_seconds']);

        $entityManager->flush();
        return $this->sendSuccess($tracker);
    }
}
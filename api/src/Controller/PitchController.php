<?php

namespace App\Controller;

use App\Entity\Pitch;
use App\Entity\Slot;
use App\Repository\PitchRepository;
use App\Repository\SlotRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionFailedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/pitches")
 *
 * @OA\Tag(name="Pitch")
 */
class PitchController extends AbstractController
{
    /**
     * List all pitches.
     * 
     * @Route("", methods={"GET"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="List all pitches.",
     *      @OA\Items(ref=@Model(type=Pitch::class, groups={"listPitches"}))
     * )
     */
    public function list(Request $request, PitchRepository $pitchRepo)
    {
        $pitches = $pitchRepo->findAll();

        return $this->json(['data' => $pitches], 200, [], [
            'groups' => ['listPitches']
        ]);
    }

    /**
     * Retrieve a specific pitch.
     * 
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\w{8}-\w{4}-\w{4}-\w{4}-\w{12}"}, options={"utf8": true})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Retrieve a specific pitch.",
     *      @Model(type=Pitch::class, groups={"retrievePitch"}))
     * )
     * OA\Response(response=404, description="Pitch does not exist.")
     * 
     * @OA\Parameter(ref="#/parameters/id")
     */
    public function retrieve(Request $request, Pitch $pitch)
    {
        if (!$pitch) {
            throw $this->createNotFoundException('Pitch does not exist.');
        }

        return $this->json(['data' => $pitch], 200, [], [
            'groups' => ['retrievePitch']
        ]);
    }

    /**
     * List all slots for a specific pitch.
     * 
     * @Route("/{id}/slots", methods={"GET"}, requirements={"id"="\w{8}-\w{4}-\w{4}-\w{4}-\w{12}"}, options={"utf8": true})
     * 
     * @OA\Response(
     *      response=200,
     *      description="List all slots for a specific pitch.",
     *      @OA\Items(ref=@Model(type=Slot::class, groups={"listSlots"}))
     * )
     * OA\Response(response=404, description="Pitch does not exist.")
     * 
     * @OA\Parameter(ref="#/parameters/id")
     */
    public function listSlots(Request $request, Pitch $pitch)
    {
        if (!$pitch) {
            throw $this->createNotFoundException('Pitch does not exist.');
        }

        return $this->json(['data' => $pitch], 200, [], [
            'groups' => ['listSlots']
        ]);
    }

    /**
     * Add slots for a specific pitch.
     * 
     * @Route("/{id}/slots", methods={"POST"}, requirements={"id"="\w{8}-\w{4}-\w{4}-\w{4}-\w{12}"}, options={"utf8": true})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Add slots for a specific pitch.",
     *      @OA\Items(ref=@Model(type=Slot::class, groups={"listSlots"}))
     * )
     * OA\Response(response=404, description="Pitch does not exist.")
     * OA\Response(response=422, description="A selected slot is unavailable.")
     * 
     * @OA\Parameter(ref="#/parameters/id")
     */
    public function addSlots(
        Request $request,
        SerializerInterface $serializer,
        Pitch $pitch, 
        PitchRepository $pitchRepo,
        SlotRepository $slotRepo, 
        ValidatorInterface $validator
    ) {
        if (!$pitch) {
            throw $this->createNotFoundException('Pitch does not exist.');
        }

        if ($request->getContent() === '[]') {
            throw new BadRequestHttpException('There are no slots to add.', null, 400);
        }

        $slots = $serializer->deserialize($request->getContent(), Slot::class . '[]', 'json', [
            'groups' => ['addSlots'],
        ]);
        
        foreach ($slots as $slot) {

            // Validate if the properties for each slot are ok.
            $errors = $validator->validate($slot);
            if (count($errors) > 0) {
                return $this->json($errors, 422);
            }

            /**
             * Check if each slot requested is available or not.
             * 
             * @throws PreconditionFailedHttpException if slot is unavailable
             */
            $slotRepo->isAvailableSlot($pitch, $slot);
            $pitch->addSlot($slot);
        }

        // If we get here we know that all slots are available.
        $pitch->addSlots($slots);

        $pitchRepo->save($pitch);

        return $this->json(['data' => $pitch], 201, [], [
            'groups' => ['addSlots']
        ]);
    }
}

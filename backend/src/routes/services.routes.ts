import { Router } from 'express';
import { getServices, getServiceById, createService } from '../controllers/services.controller';

const router = Router();

router.get('/', getServices);
router.get('/:id', getServiceById);
router.post('/', createService);

export default router;

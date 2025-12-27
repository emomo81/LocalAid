import express, { Request, Response } from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import { createClient } from '@supabase/supabase-js';

dotenv.config();

const app = express();
const port = process.env.PORT || 4000;

// Middleware
app.use(cors());
app.use(express.json());

// Supabase Setup
const supabaseUrl = process.env.SUPABASE_URL;
const supabaseKey = process.env.SUPABASE_KEY;

if (!supabaseUrl || !supabaseKey) {
    console.warn('Missing SUPABASE_URL or SUPABASE_KEY in .env file');
}

export const supabase = createClient(supabaseUrl || '', supabaseKey || '');

import servicesRoutes from './routes/services.routes';

// Routes
app.use('/api/services', servicesRoutes);

app.get('/', (req: Request, res: Response) => {
    res.send('LocalAid Backend is running!');
});

// Start Server
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});

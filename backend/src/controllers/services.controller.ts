import { Request, Response } from 'express';
import { supabase } from '../index';

export const getServices = async (req: Request, res: Response) => {
    const { category, location } = req.query;

    let query = supabase
        .from('services')
        .select('*, profiles(full_name, avatar_url)');

    if (category) {
        query = query.eq('category', category);
    }

    if (location) {
        query = query.ilike('location', `%${location}%`);
    }

    const { data, error } = await query;

    if (error) {
        return res.status(500).json({ error: error.message });
    }

    return res.status(200).json(data);
};

export const getServiceById = async (req: Request, res: Response) => {
    const { id } = req.params;

    const { data, error } = await supabase
        .from('services')
        .select('*, profiles(full_name, avatar_url, role)')
        .eq('id', id)
        .single();

    if (error) {
        return res.status(404).json({ error: 'Service not found' });
    }

    return res.status(200).json(data);
};

export const createService = async (req: Request, res: Response) => {
    const { title, description, category, price, location, worker_id, image_url } = req.body;

    const { data, error } = await supabase
        .from('services')
        .insert([
            { title, description, category, price, location, worker_id, image_url }
        ])
        .select();

    if (error) {
        return res.status(400).json({ error: error.message });
    }

    return res.status(201).json(data);
};

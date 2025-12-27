import { createClient } from '@supabase/supabase-js';

const supabaseUrl = process.env.NEXT_PUBLIC_SUPABASE_URL;
const supabaseKey = process.env.NEXT_PUBLIC_SUPABASE_ANON_KEY;

// Check for missing env vars but allow build to proceed (Next.js build time check)
if (typeof window !== 'undefined' && (!supabaseUrl || !supabaseKey)) {
    console.error('Missing Supabase Environment Variables');
}

export const supabase = createClient(
    supabaseUrl || 'https://placeholder.supabase.co',
    supabaseKey || 'placeholder-key'
);

-- Enable UUID extension
create extension if not exists "uuid-ossp";

-- PROFILES (Public data for users)
create table profiles (
  id uuid references auth.users not null primary key,
  full_name text,
  role text check (role in ('customer', 'worker')) default 'customer',
  avatar_url text,
  created_at timestamp with time zone default timezone('utc'::text, now()) not null
);

-- Access policies (RLS)
alter table profiles enable row level security;
create policy "Public profiles are viewable by everyone." on profiles for select using (true);
create policy "Users can insert their own profile." on profiles for insert with check (auth.uid() = id);
create policy "Users can update own profile." on profiles for update using (auth.uid() = id);

-- SERVICES (Listings by workers)
create table services (
  id uuid default uuid_generate_v4() primary key,
  worker_id uuid references profiles(id) not null,
  title text not null,
  description text,
  category text not null,
  price decimal(10, 2) not null,
  location text not null, -- Could be split into city/area
  image_url text,
  created_at timestamp with time zone default timezone('utc'::text, now()) not null
);

alter table services enable row level security;
create policy "Services are viewable by everyone." on services for select using (true);
create policy "Workers can insert their own services." on services for insert with check (auth.uid() = worker_id);
create policy "Workers can update own services." on services for update using (auth.uid() = worker_id);

-- BOOKINGS
create table bookings (
  id uuid default uuid_generate_v4() primary key,
  service_id uuid references services(id) not null,
  customer_id uuid references profiles(id) not null,
  worker_id uuid references profiles(id) not null, -- Denormalized for easy querying
  status text check (status in ('pending', 'confirmed', 'completed', 'cancelled')) default 'pending',
  booking_date timestamp with time zone not null,
  created_at timestamp with time zone default timezone('utc'::text, now()) not null
);

alter table bookings enable row level security;
create policy "Users can view their own bookings." on bookings for select using (auth.uid() = customer_id or auth.uid() = worker_id);
create policy "Customers can insert bookings." on bookings for insert with check (auth.uid() = customer_id);
create policy "Workers can update status." on bookings for update using (auth.uid() = worker_id);

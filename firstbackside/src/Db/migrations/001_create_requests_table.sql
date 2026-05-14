CREATE TABLE IF NOT EXISTS public.requests (
    id SERIAL PRIMARY KEY,
    brand VARCHAR(16),
    device VARCHAR(32),
    memory_count INTEGER,
    is_new BOOLEAN,
    is_repair BOOLEAN,
    battery_condition INTEGER,
    case_condition VARCHAR(256),
    screen_condition VARCHAR(256),
    is_working BOOLEAN,
    working_description VARCHAR(256),
    equipment INTEGER,
    phone VARCHAR(15) NOT NULL, 
    name VARCHAR(20) NOT NULL   
);

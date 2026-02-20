-- ============================================================
-- BID FOR USED PRODUCT - Comprehensive Seed Data
-- Tamil Nadu Context with 50+ Products
-- Generated: <?php echo date('Y-m-d H:i:s'); ?>
-- ============================================================

USE bid_for_used_product;

SET FOREIGN_KEY_CHECKS = 0;

-- Clear existing data
TRUNCATE TABLE bids;
TRUNCATE TABLE products;
TRUNCATE TABLE companies;
TRUNCATE TABLE notifications;
TRUNCATE TABLE subscriptions;
TRUNCATE TABLE reminders;
TRUNCATE TABLE messages;
TRUNCATE TABLE contact_messages;
DELETE FROM users WHERE user_id > 1;

-- ============================================================
-- COMPREHENSIVE TAMIL NADU SEED DATA
-- ============================================================

-- Companies (15 companies across Tamil Nadu)
INSERT INTO users (role, name, email, password, contact, address, status) VALUES
('company', 'Chennai Premium Auto', 'premium@chennai.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9840012345', '456 Mount Road, Chennai, TN 600002', 'active'),
('company', 'Madurai Vehicle Hub', 'vehicles@madurai.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9443067890', '12 Main Street, Madurai, TN 625001', 'active'),
('company', 'Coimbatore Motors', 'motors@cbe.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9894023456', '78 RS Puram, Coimbatore, TN 641002', 'active'),
('company', 'Salem Tractor World', 'tractors@salem.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9944056789', '23 Steel Plant Road, Salem, TN 636001', 'active'),
('company', 'Trichy Auto Traders', 'auto@trichy.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9787034567', '89 Srirangam Road, Trichy, TN 620001', 'active'),
('company', 'Erode Machinery Mart', 'machinery@erode.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9626078901', '45 Perundurai Road, Erode, TN 638001', 'active'),
('company', 'Tiruppur Industrial Equipment', 'industry@tiruppur.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9876545678', '67 Avinashi Road, Tiruppur, TN 641601', 'active'),
('company', 'Vellore Bike Centre', 'bikes@vellore.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9865098765', '34 Gandhi Road, Vellore, TN 632001', 'active'),
('company', 'Thanjavur Agri Solutions', 'agri@thanjavur.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9865432109', '12 East Main Street, Thanjavur, TN 613001', 'active'),
('company', 'Kanchipuram Luxury Cars', 'luxury@kanchi.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9841156789', '56 Silk Street, Kanchipuram, TN 631501', 'active'),
('company', 'Tuticorin Port Vehicles', 'port@tuticorin.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9443345678', '89 Beach Road, Tuticorin, TN 628001', 'active'),
('company', 'Dindigul Farm Equipment', 'farm@dindigul.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9865223456', '23 Market Road, Dindigul, TN 624001', 'active'),
('company', 'Karur Transport Solutions', 'transport@karur.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9843267890', '45 Bus Stand Road, Karur, TN 639001', 'active'),
('company', 'Tirunelveli Vehicle Depot', 'depot@tirunelveli.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9443412345', '78 Junction Road, Tirunelveli, TN 627001', 'active'),
('company', 'Hosur Heavy Equipment', 'heavy@hosur.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9865556789', '90 SIPCOT Area, Hosur, TN 635109', 'active');

-- Company details
INSERT INTO companies (user_id, company_name, owner_name, gst_number, verified_status) VALUES
(2, 'Chennai Premium Auto', 'Rajesh Kumar', '33AAACH1234F1Z1', 'verified'),
(3, 'Madurai Vehicle Hub', 'Selvam Pandian', '33BBAMV5678G2Z2', 'verified'),
(4, 'Coimbatore Motors', 'Karthik Subramanian', '33CCCMO9012H3Z3', 'verified'),
(5, 'Salem Tractor World', 'Murugan Rajan', '33DDDST3456I4Z4', 'verified'),
(6, 'Trichy Auto Traders', '  Balasubramanian', '33EEEAT7890J5Z5', 'verified'),
(7, 'Erode Machinery Mart', 'Shankar Mani', '33FFFEM1234K6Z6', 'verified'),
(8, 'Tiruppur Industrial Equipment', 'Gopal Krishnan', '33GGGTI5678L7Z7', 'verified'),
(9, 'Vellore Bike Centre', 'Dinesh Kumar', '33HHHVB9012M8Z8', 'verified'),
(10, 'Thanjavur Agri Solutions', 'Veera Raghavan', '33IIIAS3456N9Z9', 'verified'),
(11, 'Kanchipuram Luxury Cars', 'Suresh Babu', '33JJJLC7890O0Z0', 'verified'),
(12, 'Tuticorin Port Vehicles', 'Anand Raja', '33KKKPV1234P1Z1', 'verified'),
(13, 'Dindigul Farm Equipment', 'Palanisamy', '33LLLFE5678Q2Z2', 'verified'),
(14, 'Karur Transport Solutions', 'Vignesh Kumar', '33MMMTS9012R3Z3', 'verified'),
(15, 'Tirunelveli Vehicle Depot', 'Senthil Nathan', '33NNNVD3456S4Z4', 'verified'),
(16, 'Hosur Heavy Equipment', 'Prakash Reddy', '33OOORE7890T5Z5', 'verified');

-- Clients (20 clients)
INSERT INTO users (role, name, email, password, contact, address, status) VALUES
('client', 'Vijay Kumar', 'vijay.k@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000112233', 'T Nagar, Chennai, TN', 'active'),
('client', 'Priya Lakshmi', 'priya.l@yahoo.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000223344', 'Anna Nagar, Chennai, TN', 'active'),
('client', 'Arun Prasad', 'arun.p@outlook.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000334455', 'Srirangam, Trichy, TN', 'active'),
('client', 'Kavitha Ramesh', 'kavitha.r@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000445566', 'Gandhipuram, Coimbatore, TN', 'active'),
('client', 'Ravi Shankar', 'ravi.s@mail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000556677', 'Fairlands, Salem, TN', 'active'),
('client', 'Meena Sundaram', 'meena.s@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000667788', 'West Masi Street, Madurai, TN', 'active'),
('client', 'Suresh Babu', 'suresh.b@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000778899', 'Textile Colony, Tiruppur, TN', 'active'),
('client', 'Lakshmi Narayanan', 'lakshmi.n@yahoo.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000889900', 'Fort Area, Vellore, TN', 'active'),
('client', 'Karthik Raja', 'karthik.r@outlook.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9000990011', 'Big Street, Thanjavur, TN', 'active'),
('client', 'Divya Bharathi', 'divya.b@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001001122', 'Silk Market, Kanchipuram, TN', 'active'),
('client', 'Senthil Kumar', 'senthil.k@mail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001112233', 'Harbor Area, Tuticorin, TN', 'active'),
('client', 'Anjali Devi', 'anjali.d@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001223344', 'Market Street, Dindigul, TN', 'active'),
('client', 'Muthu Vel', 'muthu.v@yahoo.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001334455', 'Bus Stand Area, Karur, TN', 'active'),
('client', 'Ramya Krishnan', 'ramya.k@outlook.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001445566', 'Junction, Tirunelveli, TN', 'active'),
('client', 'Bala Murugan', 'bala.m@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001556677', 'Industrial Area, Hosur, TN', 'active'),
('client', 'Geetha Rani', 'geetha.r@mail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001667788', 'Pondy Bazaar, Chennai, TN', 'active'),
('client', 'Arjun Vikram', 'arjun.v@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001778899', 'Bypass Road, Madurai, TN', 'active'),
('client', 'Nithya Menen', 'nithya.m@yahoo.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001889900', 'Race Course, Coimbatore, TN', 'active'),
('client', 'Prakash Raj', 'prakash.r@outlook.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9001990011', 'Cherry Road, Salem, TN', 'active'),
('client', 'Sowmya Devi', 'sowmya.d@gmail.com', '$2y$10$wKn5Y8xjFVPvX5L1J7L7kO7F5V6Z9V8X7Y6W5N4M3L2K1J0I9H8G7', '9002001122', 'Main Bazaar, Erode, TN', 'active');

-- 60+ Products across all categories
INSERT INTO products (company_id, product_name, category, model, year, chassis_no, owner_details, running_duration, base_price, bid_start, bid_end, product_image, status, description) VALUES
-- 4-Wheelers (30 products)
(1, 'Maruti Swift Dzire VXI', '4-wheeler', 'VXI Petrol AT', 2020, 'MA3456789001', 'Single Owner Doctor', '35,000 km', 550000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'swift_dzire.jpg', 'open', 'Well maintained, all records available, AC cooling excellent'),
(1, 'Hyundai Creta SX', '4-wheeler', 'SX Diesel Manual', 2021, 'HY8901234562', 'First Owner Lady', '22,000 km', 1150000.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'creta.jpg', 'open', 'Showroom condition, full insurance, zero accident'),
(1, 'Honda City ZX', '4-wheeler', 'ZX CVT Petrol', 2019, 'HO5678901233', 'Second Owner', '48,000 km', 750000.00, NOW(), DATE_ADD(NOW(), INTERVAL 6 DAY), 'honda_city.jpg', 'open', 'Excellent condition, recently serviced, new tyres'),
(1, 'Toyota Fortuner', '4-wheeler', '4x4 Diesel MT', 2018, 'TO9988776654', 'Army Officer Owned', '65,000 km', 2200000.00, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'fortuner.jpg', 'open', 'Meticulously maintained, complete service history'),
(2, 'Mahindra XUV500', '4-wheeler', 'W11 Option', 2022, 'MA1122334455', 'IT Professional', '12,000 km', 1650000.00, NOW(), DATE_ADD(NOW(), INTERVAL 8 DAY), 'xuv500.jpg', 'open', 'Almost new, under warranty, premium features'),
(2, 'Tata Nexon', '4-wheeler', 'XZ+ Diesel', 2021, 'TA2233445566', 'Company Leased', '28,000 km', 950000.00, NOW(), DATE_ADD(NOW(), INTERVAL 9 DAY), 'nexon.jpg', 'open', 'Well maintained corporate asset, full service records'),
(3, 'Toyota Innova Crysta', '4-wheeler', '2.4 GX MT', 2020, 'TO1122334456', 'Fleet Owner', '85,000 km', 1450000.00, NOW(), DATE_ADD(NOW(), INTERVAL 4 DAY), 'innova.jpg', 'open', 'Tourist permit vehicle, regular maintenance'),
(3, 'Kia Seltos', '4-wheeler', 'HTX Plus', 2022, 'KI9988776655', 'Single Owner', '18,000 km', 1350000.00, NOW(), DATE_ADD(NOW(), INTERVAL 12 DAY), 'seltos.jpg', 'open', 'Premium trim, loaded with features'),
(3, 'Ford EcoSport', '4-wheeler', 'Titanium Plus', 2019, 'FO5566778899', 'Bank Manager', '42,000 km', 780000.00, NOW(), DATE_ADD(NOW(), INTERVAL 6 DAY), 'ecosport.jpg', 'open', 'Excellent condition, garage kept, no scratches'),
(4, 'Mahindra Scorpio', '4-wheeler', 'S11 4WD', 2023, 'MA9998887776', 'Businessman', '8,500 km', 1750000.00, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 'scorpio.jpg', 'open', 'Latest model, powerful performance'),
(4, 'Renault Duster', '4-wheeler', 'RXZ Diesel', 2018, 'RE6677889900', 'Adventure Enthusiast', '55,000 km', 680000.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'duster.jpg', 'open', 'Rugged and reliable, off-road ready'),
(5, 'Volkswagen Polo', '4-wheeler', 'Highline Plus', 2020, 'VW3344556677', 'College Professor', '32,000 km', 620000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'polo.jpg', 'open', 'German engineering, smooth drive'),
(5, 'Skoda Rapid', '4-wheeler', 'Elegance TSI', 2021, 'SK4455667788', 'Architect', '25,000 km', 850000.00, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'rapid.jpg', 'open', 'Spacious, comfortable, premium feel'),
(6, 'Nissan Kicks', '4-wheeler', 'XV Premium', 2020, 'NI5566778890', 'Doctor', '35,000 km', 950000.00, NOW(), DATE_ADD(NOW(), INTERVAL 8 DAY), 'kicks.jpg', 'open', 'Stylish SUV, feature loaded'),
(6, 'MG Hector', '4-wheeler', 'Sharp Diesel', 2022, 'MG6677889901', 'Business Owner', '15,000 km', 1550000.00, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 'hector.jpg', 'open', 'Connected car features, panoramic sunroof'),
(7, 'BMW 3 Series', '4-wheeler', '320d Sport Line', 2017, 'BM1122334457', 'Luxury Enthusiast', '72,000 km', 1850000.00, NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 'bmw3.jpg', 'open', 'Premium luxury, thrilling performance'),
(7, 'Audi Q3', '4-wheeler', '35 TDI Premium', 2018, 'AU2233445568', 'Entrepreneur', '58,000 km', 2100000.00, NOW(), DATE_ADD(NOW(), INTERVAL 18 DAY), 'audiq3.jpg', 'open', 'German luxury SUV, immaculate condition'),
(8, 'Mercedes C-Class', '4-wheeler', 'C220d Progressive', 2019, 'MB3344556679', 'Corporate Executive', '45,000 km', 2650000.00, NOW(), DATE_ADD(NOW(), INTERVAL 25 DAY), 'cclass.jpg', 'open', 'Ultimate luxury, perfect condition'),
(9, 'Honda WR-V', '4-wheeler', 'VX Petrol', 2020, 'HO4455667780', 'Family Use', '38,000 km', 720000.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'wrv.jpg', 'open', 'Compact SUV, great fuel economy'),
(9, 'Tata Tiago', '4-wheeler', 'XZ Plus', 2021, 'TA5566778891', 'Student', '18,000 km', 480000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'tiago.jpg', 'open', 'Perfect city car, low maintenance'),
(10, 'Volkswagen Vento', '4-wheeler', 'Highline', 2019, 'VW6677889902', 'Executive', '42,000 km', 720000.00, NOW(), DATE_ADD(NOW(), INTERVAL 8 DAY), 'vento.jpg', 'open', 'Premium sedan, great build quality'),
(10, 'Hyundai Venue', '4-wheeler', 'SX Turbo', 2022, 'HY7788990013', 'Young Professional', '12,000 km', 980000.00,  NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'venue.jpg', 'open', 'Compact SUV with turbo power'),
(11, 'Jeep Compass', '4-wheeler', 'Limited Plus', 2020, 'JP8899001124', 'Adventure Lover', '35,000 km', 1850000.00, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 'compass.jpg', 'open', '4x4 capability, premium interiors'),
(11, 'Mahindra Thar', '4-wheeler', 'LX Hard Top', 2021, 'MA9900112235', 'Off-road Enthusiast', '22,000 km', 1450000.00, NOW(), DATE_ADD(NOW(), INTERVAL 12 DAY), 'thar.jpg', 'open', 'Icon of adventure, mint condition'),
(12, 'Maruti Ertiga', '4-wheeler', 'VXI CNG', 2021, 'MA0011223346', 'Large Family', '45,000 km', 880000.00, NOW(), DATE_ADD(NOW(), INTERVAL 6 DAY), 'ertiga.jpg', 'open', '7-seater, excellent fuel economy'),
(12, 'Ford Endeavour', '4-wheeler', 'Titanium Plus 4x4', 2017, 'FO1122334458', 'Business Owner', '95,000 km', 1950000.00, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 'endeavour.jpg', 'open', 'Premium SUV, powerful performance'),
(13, 'Honda Amaze', '4-wheeler', 'VX Petrol CVT', 2020, 'HO2233445569', 'Senior Citizen', '28,000 km', 680000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'amaze.jpg', 'open', 'Reliable sedan, smooth automatic'),
(14, 'Renault Kwid', '4-wheeler', 'Climber AMT', 2021, 'RE3344556670', 'First Time Buyer', '15,000 km', 420000.00, NOW(), DATE_ADD(NOW(), INTERVAL 4 DAY), 'kwid.jpg', 'open', 'Budget friendly, smart features'),
(15, 'Hyundai i20', '4-wheeler', 'Sportz Diesel', 2019, 'HY4455667781', 'Banker', '48,000 km', 780000.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'i20.jpg', 'open', 'Premium hatchback, excellent condition'),
(16, 'Tata Harrier', '4-wheeler', 'XZ Plus Dark', 2022, 'TA5566778892', 'Architect', '18,000 km', 1850000.00, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 'harrier.jpg', 'open', 'Bold SUV, premium features'),

-- 2-Wheelers (20 products)
(1, 'Royal Enfield Classic 350', '2-wheeler', 'Gunmetal Grey', 2021, 'RE1234567891', 'Bachelor', '14,000 km', 165000.00, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), 're_classic.jpg', 'open', 'Iconic cruiser, well maintained'),
(2, 'Yamaha R15 V3', '2-wheeler', 'Racing Blue', 2022, 'YA2345678902', 'College Student', '8,500 km', 145000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'r15.jpg', 'open', 'Sporty bike, excellent condition'),
(3, 'KTM Duke 390', '2-wheeler', 'Orange', 2020, 'KT3456789013', 'Sports Enthusiast', '22,000 km', 195000.00, NOW(), DATE_ADD(NOW(), INTERVAL 6 DAY), 'duke390.jpg', 'open', 'Powerful performance, track ready'),
(4, 'Honda CB Shine', '2-wheeler', 'Black', 2021, 'HO4567890124', 'Office Commuter', '18,000 km', 72000.00, NOW(), DATE_ADD(NOW(), INTERVAL 4 DAY), 'cbshine.jpg', 'open', 'Fuel efficient, reliable daily ride'),
(5, 'TVS Apache RTR 160 4V', '2-wheeler', 'Red', 2022, 'TV5678901235', 'Young Professional', '6,500 km', 95000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'apache.jpg', 'open', 'Stylish sports bike, great performance'),
(6, 'Bajaj Pulsar NS200', '2-wheeler', 'Blue', 2021, 'BA6789012346', 'Engineering Student', '12,000 km', 115000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'pulsar.jpg', 'open', 'Versatile performer, good condition'),
(7, 'Hero Splendor Plus', '2-wheeler', 'Black Red', 2022, 'HE7890123457', 'Daily Commuter', '5,500 km', 58000.00, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), 'splendor.jpg', 'open', 'Most trusted, fuel efficient'),
(8, 'Suzuki Gixxer SF', '2-wheeler', 'Blue White', 2020, 'SU8901234568', 'Bike Lover', '16,500 km', 108000.00, NOW(), DATE_ADD(NOW(), INTERVAL 6 DAY), 'gixxer.jpg', 'open', 'Faired sports bike, smooth ride'),
(9, 'Honda Activa 6G', '2-wheeler', 'Matte Black', 2023, 'HO9012345679', 'Lady Driven', '3,200 km', 78000.00, NOW(), DATE_ADD(NOW(), INTERVAL 4 DAY), 'activa.jpg', 'open', 'Almost new, perfect for ladies'),
(10, 'TVS Jupiter', '2-wheeler', 'Starlight Blue', 2021, 'TV0123456780', 'Senior Citizen', '12,500 km', 68000.00, NOW(), DATE_ADD(NOW(), INTERVAL 4 DAY), 'jupiter.jpg', 'open', 'Comfortable scooter, easy to ride'),
(11, 'Bajaj Avenger Cruise 220', '2-wheeler', 'Black', 2020, 'BA1234567892', 'Cruiser Fan', '25,000 km', 98000.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'avenger.jpg', 'open', 'Highway cruiser, relaxed riding'),
(12, 'Royal Enfield Himalayan', '2-wheeler', 'Granite Black', 2021, 'RE2345678903', 'Adventure Rider', '18,000 km', 185000.00, NOW(), DATE_ADD(NOW(), INTERVAL 8 DAY), 'himalayan.jpg', 'open', 'Adventure tourer, ready for trips'),
(13, 'Yamaha FZ S FI', '2-wheeler', 'Dark Matte Blue', 2022, 'YA3456789014', 'City Rider', '8,000 km', 115000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'fzs.jpg', 'open', 'Muscular design, powerful engine'),
(14, 'KTM RC 200', '2-wheeler', 'White Orange', 2020, 'KT4567890125', 'Track Enthusiast', '14,500 km', 175000.00, NOW(), DATE_ADD(NOW(), INTERVAL 9 DAY), 'rc200.jpg', 'open', 'Race replica, sporty performance'),
(15, 'Honda Dio', '2-wheeler', 'Candy Jazzy Blue', 2022, 'HO5678901236', 'College Girl', '4,500 km', 62000.00, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), 'dio.jpg', 'open', 'Stylish scooter, great mileage'),
(16, 'Hero Xtreme 160R', '2-wheeler', 'Stealth Black', 2021, 'HE6789012347', 'Fitness Trainer', '11,000 km', 95000.00, NOW(), DATE_ADD(NOW(), INTERVAL 5 DAY), 'xtreme.jpg', 'open', 'Sharp looks, agile handling'),
(1, 'Suzuki Access 125', '2-wheeler', 'Pearl Red', 2021, 'SU7890123458', 'Working Women', '9,500 km', 70000.00, NOW(), DATE_ADD(NOW(), INTERVAL 4 DAY), 'access.jpg', 'open', 'Comfortable daily commute'),
(2, 'Bajaj Dominar 400', '2-wheeler', 'Aurora Green', 2020, 'BA8901234569', 'Tourer', '28,000 km', 155000.00, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'dominar.jpg', 'open', 'Long distance tourer, reliable'),
(3, 'TVS XL100', '2-wheeler', 'Black', 2022, 'TV9012345670', 'Small Business', '6,000 km', 42000.00, NOW(), DATE_ADD(NOW(), INTERVAL 3 DAY), 'xl100.jpg', 'open', 'Mileage king, low maintenance'),
(4, 'Royal Enfield Thunderbird 350X', '2-wheeler', 'Whimsical White', 2019, 'RE0123456781', 'Cruiser Lover', '32,000 km', 138000.00, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'thunderbird.jpg', 'open', 'Classic cruiser, highway beast'),

-- Machinery (15 products)
(5, 'Mahindra 575 DI Tractor', 'machinery', 'Bhoomiputra 3WD', 2020, 'MH575123451', 'Farmer', '1,800 hrs', 525000.00, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 'mahindra_tractor.jpg', 'open', 'Powerful tractor, ready for farming'),
(5, 'Swaraj 744 FE Tractor', 'machinery', '744 FE', 2022, 'SW744234562', 'Agriculture Business', '650 hrs', 620000.00, NOW(), DATE_ADD(NOW(), INTERVAL 18 DAY), 'swaraj.jpg', 'open', 'Latest model, fuel efficient'),
(6, 'JCB 3DX Super Excavator', 'machinery', '3DX Super Backhoe', 2019, 'JC3DX345673', 'Construction Company', '4,200 hrs', 1950000.00, NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 'jcb.jpg', 'open', 'Heavy duty, excellent working condition'),
(7, 'Tata Hitachi Excavator', 'machinery', 'ZAXIS 33U', 2020, 'TH33U456784', 'Infrastructure Project', '3,500 hrs', 1650000.00, NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 'hitachi.jpg', 'open', 'Reliable excavator, well maintained'),
(8, 'John Deere 5050 D Tractor', 'machinery', '5050 D 4WD', 2021, 'JD5050567895', 'Large Farm Owner', '1,200 hrs', 780000.00, NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), 'johndeere.jpg', 'open', 'Premium quality, advanced features'),
(9, 'New Holland 3630 TX Tractor', 'machinery', '3630 TX Plus', 2022, 'NH3630678906', 'Progressive Farmer', '550 hrs', 720000.00, NOW(), DATE_ADD(NOW(), INTERVAL 18 DAY), 'newholland.jpg', 'open', 'Modern tractor, powerful engine'),
(10, 'L&T Komatsu PC71 Excavator', 'machinery', 'PC71', 2018, 'LT71789017', 'Mining Company', '5,800 hrs', 1350000.00, NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 'komatsu.jpg', 'open', 'Robust excavator for tough jobs'),
(11, 'Power Tiller VST Shakti', 'machinery', 'VSD 149 DI', 2023, 'VS149890128', 'Small Farmer', '320 hrs', 185000.00, NOW(), DATE_ADD(NOW(), INTERVAL 12 DAY), 'powertiller.jpg', 'open', 'Perfect for small farms, new condition'),
(12, 'Harvester Kartar 4000', 'machinery', '4000 Combine', 2019, 'KT4000901239', 'Commercial Harvesting', '2,500 hrs', 1850000.00, NOW(), DATE_ADD(NOW(), INTERVAL 22 DAY), 'harvester.jpg', 'open', 'Efficient harvesting, time saver'),
(13, 'Industrial Sewing Machine Juki', 'machinery', 'DDL-8100', 2021, 'JK8100012340', 'Garment Factory', '2 years', 28000.00, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'juki.jpg', 'open', 'High speed, perfect for production'),
(14, 'Overlock Machine Siruba', 'machinery', '747K', 2020, 'SI747K123451', 'Boutique', '3 years', 38000.00, NOW(), DATE_ADD(NOW(), INTERVAL 10 DAY), 'siruba.jpg', 'open', 'Professional grade, reliable'),
(15, 'Generator Set Kirloskar', 'machinery', 'KG1-62.5AS', 2021, 'KI6225234562', 'Hotel', '1,800 hrs', 185000.00, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY), 'generator.jpg', 'open', 'Silent operation, fuel efficient'),
(16, 'Concrete Mixer Ajax', 'machinery', 'Fiori DB 260', 2020, 'AJ260345673', 'Builder', '2,200 hrs', 165000.00, NOW(), DATE_ADD(NOW(), INTERVAL 12 DAY), 'mixer.jpg', 'open', 'Heavy duty for construction'),
(1, 'Mahindra Earth Master Loader', 'machinery', 'SX', 2022, 'ME456784', 'Earth Moving Company', '1,500 hrs', 1750000.00, NOW(), DATE_ADD(NOW(), INTERVAL 20 DAY), 'loader.jpg', 'open', 'Powerful loader, excellent condition'),
(2, 'Rotavator Fieldking', 'machinery', 'FB-180', 2023, 'FK180567895', 'Modern Farmer', '150 hrs', 95000.00, NOW(), DATE_ADD(NOW(), INTERVAL 12 DAY), 'rotavator.jpg', 'open', 'Farm implement, like new');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- End of Comprehensive Seed Data
-- Total Products: 65 (30 Cars + 20 Bikes + 15 Machinery)
-- ============================================================

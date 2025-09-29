-- Seed data for `users`
START TRANSACTION;

INSERT INTO `users`
  (`username`, `email`, `password`, `last_login`, `is_active`, `created_at`, `updated_at`)
VALUES
-- Active admins / power users
('admin',       'admin@example.com',       '$2b$12$0M4b9j1gTgVj0b1kzR1fUe1bJKb2c6y8r0I3qg9b2b8t0S2yE7JQm', '2025-09-20 08:15:00', TRUE,  '2025-01-05 10:00:00', '2025-09-20 08:15:00'),
('alice',       'alice@example.com',       '$2b$12$1qW8Qe3fGkZr3J7lJm7m8uKp2mJk6f2s1H4e9yQw2e7Vf9Zc0U8Me', '2025-09-21 14:03:22', TRUE,  '2025-02-10 09:42:00', '2025-09-21 14:03:22'),
('bob',         'bob@example.com',         '$2b$12$Qn9rT4yLk6Zt2X1aV0bYru8b1nGxD4pQ9tF2nXkC5rX6pQ8e3YhWS', '2025-08-29 18:41:09', TRUE,  '2025-03-18 11:10:00', '2025-08-29 18:41:09'),

-- A few regular users
('charlie',     'charlie@example.com',     '$2b$12$Zs1L3k8Vw0Qp9Yh2Rj5t0uE4zVg2nF1cD6h9Kj3Lq8Nw5Rr0A7y2i', '2025-07-11 07:28:44', TRUE,  '2025-04-02 16:25:00', '2025-07-11 07:28:44'),
('diana',       'diana@example.com',       '$2b$12$Ee7Pp1Lk9Qw2Zz8Tt6Yy0oP3sLd8Fj1Aq9Hn5Gm4Vb3Cx2Kk7Jf5S', '2025-09-10 12:02:30', TRUE,  '2025-05-12 13:55:00', '2025-09-10 12:02:30'),
('edgar',       'edgar@example.com',       '$2b$12$Mb4Tr8Qw1Lp6Vx2Zz9Aa5cR3fDn7Uj2Kq5Wm8Ht6Ry4Po1Nv2GkXW', '2025-05-03 20:17:05', TRUE,  '2025-05-19 08:05:00', '2025-05-03 20:17:05'),
('fatima',      'fatima@example.com',      '$2b$12$Hy7Jk2Qw9Tt1Pp3Ll6Vv0aZ1mXc8Qe2Bv6Nr0Uw4Ld7Fs9Gg1Qe1C', NULL,                        TRUE,  '2025-06-01 10:10:00', '2025-06-01 10:10:00'),
('george',      'george@example.com',      '$2b$12$Wt2Qq8Lm1Na6Rb3Yy9Uu4hC7xVn2Pe5Ls8Do1Kk3Jf6Hg9Tt4Zp3M', '2025-09-01 09:00:00', TRUE,  '2025-06-22 15:30:00', '2025-09-01 09:00:00'),

-- Inactive / never logged in yet
('hannah',      'hannah@example.com',      '$2b$12$Ck4Xv7Qp2Lo8Jd5Ff0Aa6mN3zQw1Er9Ty4Ui7Op6Qe3Bn0Lh5Sx5u', NULL,                        FALSE, '2025-07-10 12:00:00', '2025-07-10 12:00:00'),
('ivan',        'ivan@example.com',        '$2b$12$Jd8Mn3Qw5Lp0Sk2Tt7Vv1aZ4xNc6Qe8Br2Po5Lk7Jg9Hh1Tt3Yu8K', NULL,                        FALSE, '2025-07-25 09:45:00', '2025-07-25 09:45:00'),

-- Recently created
('julia',       'julia@example.com',       '$2b$12$Nr1Qe8Lk4Vm6Xc2Za7Ut9oP3yLs8Dn4Fr0Pw6Kk2Jf7Hg9Tt3QwEe', '2025-09-27 21:14:33', TRUE,  '2025-09-15 08:20:00', '2025-09-27 21:14:33'),
('kevin',       'kevin@example.com',       '$2b$12$Ts8Qw2Lp6Vn1Km4Az9Ur5eC7xQe2Bn5Ls8Do1Kk3Jf6Hg9Tt4Zp3M', NULL,                        TRUE,  '2025-09-28 10:00:00', '2025-09-28 10:00:00');

COMMIT;

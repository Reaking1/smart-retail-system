package com.smartretail.controllers;

import com.smartretail.models.Admin;
import com.smartretail.services.AdminService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.web.bind.annotation.*;

import java.util.Optional;

@RestController
@RequestMapping("/api/auth/admins")
public class AdminAuthController {

    @Autowired
    private AdminService adminService;

    private final BCryptPasswordEncoder passwordEncoder = new BCryptPasswordEncoder();

    // Register new admin
    @PostMapping("/register")
    public Admin register(@RequestBody Admin admin) {
        // Hash password before saving
        admin.setPassword(passwordEncoder.encode(admin.getPassword()));
        return adminService.saveAdmin(admin);
    }

    // Login existing admin
    @PostMapping("/login")
    public String login(@RequestBody Admin loginRequest) {
        Optional<Admin> adminOpt = adminService.findByUsername(loginRequest.getUsername());

        if (adminOpt.isPresent()) {
            Admin admin = adminOpt.get();
            if (passwordEncoder.matches(loginRequest.getPassword(), admin.getPassword())) {
                return "Login successful! Welcome admin " + admin.getUsername();
                // Later: return a JWT token here
            }
        }

        return "Invalid admin credentials";
    }
}

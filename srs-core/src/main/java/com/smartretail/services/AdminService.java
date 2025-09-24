package com.smartretail.services;

import com.smartretail.models.Admin;
import com.smartretail.repositores.AdminRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.Optional;

@Service
public class AdminService {

    @Autowired
    private AdminRepository adminRepository;

    // Save new or updated admin
    public Admin saveAdmin(Admin admin) {
        return adminRepository.save(admin);
    }

    // Find by ID
    public Optional<Admin> getAdminById(Long id) {
        return adminRepository.findById(id);
    }

    // Find by username
    public Optional<Admin> findByUsername(String username) {
        return adminRepository.findByUsername(username);
    }

    // Delete admin by ID
    public void deleteAdmin(Long id) {
        adminRepository.deleteById(id);
    }
}

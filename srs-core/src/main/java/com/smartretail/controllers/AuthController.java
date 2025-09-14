package com.smartretail.controllers;


import com.smartretail.models.User;
import com.smartretail.services.UserService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.Optional;



@RestController
@RequestMapping("/api/auth")
public class AuthController {
     @Autowired
    private UserService userService;

    @PostMapping("/register")
    public User register(@RequestBody User user) {
        return userService.registerUser(user);
    }

    @PostMapping("/login")
    public String login(@RequestBody User loginRequest) {
        Optional<User> userOpt = userService.authenticate(loginRequest.getEmail(), loginRequest.getPassword());
        if (userOpt.isPresent()) {
            // later we can return a JWT token here
            return "Login successful! Welcome " + userOpt.get().getEmail();
        } else {
            return "Invalid credentials";
        }
    }
}

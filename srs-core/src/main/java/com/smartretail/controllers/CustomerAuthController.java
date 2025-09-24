package com.smartretail.controllers;


import com.smartretail.models.Customer;
import com.smartretail.services.CustomerService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;


@RestController
@RequestMapping("/api/auth/customers")
public class CustomerAuthController {
     @Autowired
    private CustomerService customerService;

    @PostMapping("/register")
    public Customer register(@RequestBody Customer customer) {
        return customerService.saveCustomer(customer);
    }

    @PostMapping("/login")
    public String login(@RequestBody Customer loginRequest) {
        boolean valid = customerService.authenticate(loginRequest.getEmail(), loginRequest.getPassword());
        if (valid) {
            return "Login successful! Welcome " + loginRequest.getEmail();
        } else {
            return "Invalid credentials";
        }
    }
}

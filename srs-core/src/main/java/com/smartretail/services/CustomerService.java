package com.smartretail.services;

import com.smartretail.models.Customer;
import com.smartretail.repositores.CustomerRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.stereotype.Service;

import java.util.List;

@Service
public class CustomerService {

    @Autowired
    private CustomerRepository customerRepository;

    private BCryptPasswordEncoder passwordEncoder = new BCryptPasswordEncoder();

    public List<Customer> getAllCustomers() {
        return customerRepository.findAll();
    }

    public Customer getCustomerById(Long id) {
        return customerRepository.findById(id).orElse(null);
    }

    public Customer getCustomerByEmail(String email) {
        return customerRepository.findByEmail(email);
    }

    public Customer saveCustomer(Customer customer) {
        // Hash the password before saving
        customer.setPassword(passwordEncoder.encode(customer.getPassword()));
        return customerRepository.save(customer);
    }

    public boolean authenticate(String email, String rawPassword) {
        Customer customer = customerRepository.findByEmail(email);
        return customer != null && passwordEncoder.matches(rawPassword, customer.getPassword());
    }

    public void deleteCustomer(Long id) {
        customerRepository.deleteById(id);
    }
}

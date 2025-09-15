package com.smartretail.srs_core;

import com.smartretail.models.TestCustomer;
import com.smartretail.repositores.TestCustomerRepository;
import org.springframework.boot.CommandLineRunner;
import org.springframework.stereotype.Component;

@Component
public class DatabaseTestRunner implements CommandLineRunner {

    private final TestCustomerRepository testCustomerRepository;

    public DatabaseTestRunner(TestCustomerRepository testCustomerRepository) {
        this.testCustomerRepository = testCustomerRepository;
    }

    @Override
    public void run(String... args) throws Exception {
        System.out.println("=== TESTING DATABASE CONNECTION ===");

        // Insert a test customer
        TestCustomer c = new TestCustomer();
        c.setName("Test User");
        testCustomerRepository.save(c);

        // Read all test customers
        for (TestCustomer tc : testCustomerRepository.findAll()) {
            System.out.println("Test Customer: " + tc.getName());
        }

        System.out.println("=== TEST COMPLETE ===");
    }
}

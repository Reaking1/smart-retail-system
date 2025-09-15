package com.smartretail.repositores;

import com.smartretail.models.TestCustomer;
import org.springframework.data.jpa.repository.JpaRepository;


public interface TestCustomerRepository extends JpaRepository<TestCustomer, Long> {
    
}

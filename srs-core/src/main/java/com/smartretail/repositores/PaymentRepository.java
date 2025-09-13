package com.smartretail.repositores;

import com.smartretail.models.Payment;
import com.smartretail.models.Order;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface PaymentRepository extends JpaRepository<Payment, Long> {
    // Example: find payment by order
    Payment findByOrder(Order order);
}

package com.smartretail.services;

import com.smartretail.models.Order;
import com.smartretail.models.Payment;
import com.smartretail.repositores.PaymentRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

@Service
public class PaymentService {

    @Autowired
    private PaymentRepository paymentRepository;

    public Payment getPaymentByOrder(Order order) {
        return paymentRepository.findByOrder(order);
    }

    public Payment savePayment(Payment payment) {
        return paymentRepository.save(payment);
    }

    public void deletePayment(Long id) {
        paymentRepository.deleteById(id);
    }
}

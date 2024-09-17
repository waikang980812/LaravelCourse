import { computed, isRef } from "vue";

export const useMonthlyPayment = (total, interestRate, duration) => {
    const monthlyPayment = computed(() => {
        const principle = Number(isRef(total) ? total.value : total); // Ensure this is a number
        const monthlyInterest =
            Number(isRef(interestRate) ? interestRate.value : interestRate) /
            100 /
            12;
        const numberOfPaymentMonths =
            Number(isRef(duration) ? duration.value : duration) * 12;

        const numerator =
            principle *
            monthlyInterest *
            Math.pow(1 + monthlyInterest, numberOfPaymentMonths);
        const denominator =
            Math.pow(1 + monthlyInterest, numberOfPaymentMonths) - 1;

        const payment = numerator / denominator;
        return payment;
    });

    const totalPaid = computed(() => {
        return (
            Number(isRef(duration) ? duration.value : duration) *
            12 *
            monthlyPayment.value
        );
    });

    const totalInterest = computed(
        () => totalPaid.value - Number(isRef(total) ? total.value : total)
    );

    return { monthlyPayment, totalPaid, totalInterest };
};

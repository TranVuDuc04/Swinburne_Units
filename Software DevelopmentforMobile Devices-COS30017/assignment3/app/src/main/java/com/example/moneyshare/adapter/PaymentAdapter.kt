package com.example.moneyshare.adapter

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.TextView
import androidx.recyclerview.widget.RecyclerView
import com.example.moneyshare.data.Payment
import com.example.moneyshare.R

class PaymentAdapter(private val payments: List<Payment>) : RecyclerView.Adapter<PaymentAdapter.PaymentViewHolder>() {

    class PaymentViewHolder(itemView: View) : RecyclerView.ViewHolder(itemView) {
        val tvAmount: TextView = itemView.findViewById(R.id.tvAmount)
        val tvDescription: TextView = itemView.findViewById(R.id.tvDescription)
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): PaymentViewHolder {
        val view = LayoutInflater.from(parent.context).inflate(R.layout.rc_payment_card, parent, false) // inflate layout
        return PaymentViewHolder(view)
    }

    override fun onBindViewHolder(holder: PaymentViewHolder, position: Int) {
        val payment = payments[position] // get payment data
        holder.tvAmount.text = payment.amount.toString() // set amount text
        holder.tvDescription.text = payment.description // set description text
    }

    override fun getItemCount(): Int {
        return payments.size // return total payment count
    }
}

package com.example.moneyshare.adapter

import android.graphics.Color
import android.view.LayoutInflater
import android.view.ViewGroup
import androidx.recyclerview.widget.RecyclerView
import com.example.moneyshare.data.ParticipantData
import com.example.moneyshare.databinding.RcParticipantCardBinding


class ParticipantAdapter(
    private val participants: List<ParticipantData>,
    private val onClick: (ParticipantData) -> Unit // lambda for click action
) : RecyclerView.Adapter<ParticipantAdapter.ParticipantViewHolder>() {

    class ParticipantViewHolder(val binding: RcParticipantCardBinding) : RecyclerView.ViewHolder(binding.root)

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): ParticipantViewHolder {
        val binding = RcParticipantCardBinding.inflate(LayoutInflater.from(parent.context), parent, false) // inflate view
        return ParticipantViewHolder(binding)
    }

    override fun onBindViewHolder(holder: ParticipantViewHolder, position: Int) {
        val participant = participants[position] // get participant data
        holder.binding.tvParticipantName.text = participant.name ?: "Participant Name" // set name

        // set the amount owed
        val amountOwed = participant.amountOwed
        val owedText = if (amountOwed < 0) {
            "Money owed: $${String.format("%.2f", -amountOwed)}" // owed money
        } else {
            "Has to pay: $${String.format("%.2f", amountOwed)}" // owes money
        }
        holder.binding.tvOwedAmounts.text = owedText // display amount

        // change card background color based on amount owed
        when {
            amountOwed > 0 -> holder.binding.root.setCardBackgroundColor(Color.parseColor("#ffe6f6")) // owes money
            amountOwed < 0 -> holder.binding.root.setCardBackgroundColor(Color.parseColor("#dcfff2")) // owed money
            else -> holder.binding.root.setCardBackgroundColor(Color.parseColor("#e4f5f9")) // no money owed
        }

        // set click listener to trigger the action
        holder.binding.root.setOnClickListener {
            onClick(participant)
        }
    }

    override fun getItemCount() = participants.size // return item count
}

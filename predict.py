import sys
import os
import joblib

# ==========================================
# PROJECT DIRECTORY
# ==========================================

BASE_DIR = os.path.dirname(os.path.abspath(__file__))


# ==========================================
# LOAD MODEL
# ==========================================

model = joblib.load(
    os.path.join(BASE_DIR, "loan_model.pkl")
)


# ==========================================
# LOAD EMPLOYMENT ENCODER
# ==========================================

employment_encoder = joblib.load(
    os.path.join(BASE_DIR, "employment_encoder.pkl")
)


# ==========================================
# CHECK INPUTS
# ==========================================

if len(sys.argv) != 6:

    print("ERROR: Invalid input")
    sys.exit(1)


# ==========================================
# GET INPUT DATA
# ==========================================

income = float(sys.argv[1])

credit_score = int(sys.argv[2])

loan_amount = float(sys.argv[3])

age = int(sys.argv[4])

employment = sys.argv[5].strip()


# ==========================================
# CONVERT EMPLOYMENT
# ==========================================

try:

    employment_encoded = employment_encoder.transform(
        [employment]
    )[0]

except Exception as e:

    print("ERROR: Invalid employment type - " + str(e))

    sys.exit(1)


# ==========================================
# MODEL PREDICTION
# ==========================================

try:

    input_data = [[
        income,
        credit_score,
        loan_amount,
        age,
        employment_encoded
    ]]

    result = model.predict(input_data)

    prediction = int(result[0])


    # No = 0
    # Yes = 1

    if prediction == 1:

        print("Eligible")

    else:

        print("Not Eligible")


except Exception as e:

    print("ERROR: Prediction failed - " + str(e))

    sys.exit(1)
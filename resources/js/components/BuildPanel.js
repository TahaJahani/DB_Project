import { TextField } from '@mui/material';
import React from 'react';
import Expandabl from './Expandable'

export default function BuildPanel() {

    const [step, setStep] = React.useState(0)

    return (
        <div>
            <Expandabl title="Query" expanded={step === 0} onExpand={() => { }}>
                <TextField label="Query" />
            </Expandabl>
        </div>
    );
}

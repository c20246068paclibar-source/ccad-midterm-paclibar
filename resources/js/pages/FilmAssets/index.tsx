import { Head } from '@inertiajs/react';

interface FilmAsset {
    id: number;
    equipment_tag: string;
    equipment_category: string;
    daily_rate: string;
    status: string;
}

interface Props {
    filmAssets: FilmAsset[];
}

export default function Index({ filmAssets }: Props) {
    return (
        <>
            <Head title="Film Assets" />

            <div className="min-h-screen bg-gray-100 p-8">
                <div className="mx-auto max-w-7xl">
                    <h1 className="mb-6 text-3xl font-bold text-gray-900">
                        Film Assets
                    </h1>

                    <div className="overflow-hidden rounded-lg bg-white shadow">
                        <table className="w-full text-left">
                            <thead className="bg-gray-200">
                                <tr>
                                    <th className="p-4">Equipment Tag</th>
                                    <th className="p-4">Category</th>
                                    <th className="p-4">Daily Rate</th>
                                    <th className="p-4">Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                {filmAssets.length > 0 ? (
                                    filmAssets.map((asset) => (
                                        <tr key={asset.id} className="border-t">
                                            <td className="p-4">{asset.equipment_tag}</td>
                                            <td className="p-4">{asset.equipment_category}</td>
                                            <td className="p-4">₱{asset.daily_rate}</td>
                                            <td className="p-4">{asset.status}</td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4} className="p-6 text-center text-gray-500">
                                            No film assets found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </>
    );
}
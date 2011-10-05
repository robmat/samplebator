#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "GDataServiceGoogleYouTube.h"

@interface CategoriesVC : VCBase {
	IBOutlet UILabel* houseCount;
	IBOutlet UILabel* gardenCount;
	IBOutlet UILabel* toolsCount;
	IBOutlet UILabel* personalCount;
	IBOutlet UILabel* electricalCount;
	IBOutlet UILabel* miscCount;
	GDataServiceGoogleYouTube* ytService;
}

@property(nonatomic,retain) IBOutlet UILabel* houseCount;
@property(nonatomic,retain) IBOutlet UILabel* gardenCount;
@property(nonatomic,retain) IBOutlet UILabel* toolsCount;
@property(nonatomic,retain) IBOutlet UILabel* personalCount;
@property(nonatomic,retain) IBOutlet UILabel* electricalCount;
@property(nonatomic,retain) IBOutlet UILabel* miscCount;
@property(nonatomic,retain) GDataServiceGoogleYouTube* ytService;

- (void)entryListFetchTicket: (GDataServiceTicket *)ticket finishedWithFeed: (GDataFeedBase *)feed error: (NSError*) error;
- (IBAction) householdAction: (id) sender;
- (IBAction) gardenToolsAction: (id) sender;
- (IBAction) electricalAction: (id) sender;
- (IBAction) toolsAction: (id) sender;
- (IBAction) personalAction: (id) sender;
- (IBAction) miscAction: (id) sender;

@end

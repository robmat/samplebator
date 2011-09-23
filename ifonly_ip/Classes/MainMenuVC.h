#import <UIKit/UIKit.h>
#import "VCBase.h"
#import "GDataServiceGoogleYouTube.h"

@interface MainMenuVC : VCBase {
	
	GDataServiceGoogleYouTube* ytService;
}

@property(nonatomic,retain) GDataServiceGoogleYouTube* ytService;

- (IBAction) recordMovieAction: (id) sender;
- (IBAction) householdAction: (id) sender;
- (IBAction) gardenToolsAction: (id) sender;
- (IBAction) electricalAction: (id) sender;
- (IBAction) toolsAction: (id) sender;
- (IBAction) personalAction: (id) sender;
- (IBAction) miscAction: (id) sender;
- (IBAction) demoAction: (id) sender;
- (IBAction) competitionAction: (id) sender;

@end

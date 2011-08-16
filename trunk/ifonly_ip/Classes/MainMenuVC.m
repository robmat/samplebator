#import "MainMenuVC.h"
#import "ChooseVideoSourceVC.h"
#import "LatestVideosByCategoryVC.h"

@implementation MainMenuVC

- (void)viewDidLoad {
    [super viewDidLoad];
	backBtn.hidden = YES;
}

- (IBAction) recordMovieAction: (id) sender {
	ChooseVideoSourceVC* cvvc = [[ChooseVideoSourceVC alloc] init];
	[self.navigationController pushViewController:cvvc animated:YES];
	[cvvc release];
}
- (IBAction) householdAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Household Products";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) gardenToolsAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Garden Products";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) electricalAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Electrical Goods";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) toolsAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Tools/Machinery";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) personalAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Personal Products";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (IBAction) miscAction: (id) sender {
	LatestVideosByCategoryVC* lvbcvc = [[LatestVideosByCategoryVC alloc] initWithNibName:nil bundle:nil];
	lvbcvc.category = @"Miscelaneous";
	[self.navigationController pushViewController:lvbcvc animated:YES];
	[lvbcvc release];
}
- (void)dealloc {
    [super dealloc];
}

@end
